<?php

namespace App\Http\Controllers;

use App\DataTables\WebhooksDataTable;
use App\Events\NewWebhookCall;
use App\Field;
use App\Webhook;
use App\WebhookCall;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WebhooksDataTable $dataTable)
    {
        return $dataTable->render('layouts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields = Field::orderBy('label', 'asc')->get();
        return view('webhooks.create')->withFields($fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:webhooks',
            'relative_url' => 'required|string|unique:webhooks|max:60|min:3',
            'json' => 'required|json',
            'json_mapping' => 'required|json'
        ]);

        $webhook = new Webhook([
            'name' => $request->name,
            'relative_url' => $request->relative_url,
            'token' => $request->token,
            'json' => $request->json,
            'json_mapping' => $request->json_mapping
        ]);

        $webhook->save();

        return response()->json(['redirect' => route('webhooks.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function show(Webhook $webhook)
    {
        return View('webhooks.show')->withWebhook($webhook);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function edit(Webhook $webhook)
    {
        $fields = Field::orderBy('label', 'asc')->get();
        return View('webhooks.edit')->withWebhook($webhook)
                                    ->withModel($webhook)
                                    ->withFields($fields);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Webhook $webhook)
    {
        $this->validate($request, [
            'name' => 'required|unique:webhooks,id,'.$webhook->id,
            'relative_url' => 'required|string|max:60|min:3|unique:webhooks,id,'.$webhook->id,
            //'json' => 'required|json'
        ]);

        $webhook->fill([
            'name' => $request->name,
            'relative_url' => $request->relative_url,
            'token' => $request->token,
            'json' => $request->json,
            'json_mapping' => $request->json_mapping
        ]);

        $webhook->save();

        return response()->json(['redirect' => route('webhooks.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Webhook $webhook)
    {
        $webhook->delete();
        return redirect()->action('WebhookController@index');
    }

    public function webhook(Request $request, String $url) {
        /* Obtém a webhook de acordo com a URL informada */
        $webhook = $this->getWebhookByUrl($url);
        /* Se encontrar a webhook processa a requisição */
        if ($webhook) {
            /* se a webhook for protegida por token, verifica se o mesmo é válido */
            if ($webhook->token) {
                $data = json_decode(json_encode($request->json()->all()));
                /* se o token bater, processa a requisição */
                if ($webhook->token == $data->token) {
                    /* armazena a chamada a webhook */
                    $this->storeWebhookCall($webhook, $data);

                    return response()->json([
                        'code' => 200,
                        'status' => 'ok'
                    ]);
                } else {
                    /* se o token não conferir, retorna um erro 403 */
                    return response()->json([
                        'code' => 403,
                        'status' => 'access denied'
                    ]);
                }
            }
            /* se a webhook não for protegida por token, processa a requisição */
            $this->storeWebhookCall($webhook, $data);

            return response()->json([
                'code' => 200,
                'status' => 'ok'
            ]);
        } else {
            /* se não encontrar retorna um erro 404 */
            return response()->json([
                'code' => 404,
                'status' => 'not found'
            ]);
        }
    }

    private function getWebhookByUrl($url) {
        return Webhook::where('relative_url', $url)->first();
    }

    private function storeWebhookCall(Webhook $webhook, stdClass $data) {
        try {
            $mapped_data = $this->mapWebhookCallData($webhook, $data);

            $webhookCall = new WebhookCall([
                'webhook_id' => $webhook->id,
                'data' => json_encode($data),
                'mapped_data' => $mapped_data
            ]);

            //se houver produto novo, cadastra o mesmo


            //se houver boleto, cadastra o mesmo

            $webhookCall->save();
        } catch (\Exception $e) {
            Log::emergency($e);
        } finally {
            event(new NewWebhookCall($webhookCall));
        }
    }

    private function mapWebhookCallData(Webhook $webhook, stdClass $data) {
        $result = [];

        $mappings = json_decode($webhook->json_mapping, true);
        foreach ($mappings as $mapping) {
            $aux = $this->parseStdAttr($data, $mapping['remoteField']);
            if ($mapping['function']) {
                switch ($mapping['function']) {
                    case 'firstName':
                        $result[$mapping['localField']['field_name']] = $this->firstName($aux);
                        break;

                    case 'shortUrl':
                        $result[$mapping['localField']['field_name']] = $this->shortUrl($aux);
                        break;
                }
            } else {
                $result[$mapping['localField']['field_name']] = $aux;
            }
        }
        return json_encode($result);
    }

    private function firstName(String $fullName) {
        return explode(' ', $fullName)[0];
    }

    private function shortURL(String $url) {
        return (string)URLShortener::shorten($url);
    }

    private function parseStdAttr(StdClass $data, String $stdAttr) {
        $attrs = explode('.', $stdAttr);
        $res = $data;
        foreach ($attrs as $attr) {
            $res = $res->$attr;
        }

        return $res;

        /* return array_reduce($attrs, function($res, $item) {
            $res .= '->{'.$item.'}';
            return $res;
        }); */
    }

    public function getWebhook(Webhook $webhook) {
        return response()->json($webhook);
    }

    public function getWebhooks() {
        return response()->json(Webhook::orderBy('name', 'asc')->get());
    }
}
