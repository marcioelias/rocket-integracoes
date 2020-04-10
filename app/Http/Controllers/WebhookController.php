<?php

namespace App\Http\Controllers;

use App\DataTables\WebhooksDataTable;
use App\Events\NewWebhookCall;
use App\Field;
use App\Product;
use App\Webhook;
use App\WebhookCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        ], [], [
            'name' => 'Nome',
            'relative_url' => 'URL',
            'json' => 'JSON',
            'json_mapping' => 'Mapeamento de Dados'
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
        ], [], [
            'name' => 'Nome',
            'relative_url' => 'URL',
            'json' => 'JSON',
            'json_mapping' => 'Mapeamento de Dados'
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
                /* se o token bater, processa a requisição */
                if ($webhook->token == $request->token) {
                    /* armazena a chamada a webhook */
                    $this->storeWebhookCall($webhook, $request->all());

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
            $this->storeWebhookCall($webhook, $request->all());

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

    private function storeWebhookCall(Webhook $webhook, array $data) {
        try {
            $mapped_data = $this->mapWebhookCallData($webhook, $data);

            $aux = json_decode($mapped_data, true);

            $webhookCall = new WebhookCall([
                'webhook_id' => $webhook->id,
                'data' => json_encode($data),
                'mapped_data' => $mapped_data,
                'transaction_code' => $aux['webhook_transaction_code']
            ]);

            //se houver produto novo, cadastra o mesmo
            $product = Product::FirstOrCreate(
                ['product_code' => $aux['product_code'], 'webhook_id' => $webhook->id],
                ['name' => $aux['product_name']]
            );

            //se houver boleto, cadastra o mesmo

            $webhookCall->save();

            event(new NewWebhookCall($webhookCall, $product));
        } catch (\Exception $e) {
            Log::emergency($e);
        }
    }

    private function mapWebhookCallData(Webhook $webhook, array $data) {
        $result = [];

        $mappings = json_decode($webhook->json_mapping, true);
        foreach ($mappings as $mapping) {
            $aux = $this->parseStdAttr($data, $mapping['remoteField']);
            if ($mapping['function']) {
                switch ($mapping['function']) {
                    case 'firstName':
                        $result[$mapping['localField']['field_name']] = $this->firstName($aux);
                        break;

                    case 'shortURL':
                        if ($aux) {
                            $result[$mapping['localField']['field_name']] = $this->shortUrl($aux);
                        } else {
                            $result[$mapping['localField']['field_name']] = '';
                        }

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
        return (new ShortUrlController)->shortUrl($url);
    }

    private function parseStdAttr(array $data, String $stdAttr) {
        $attrs = explode('.', $stdAttr);
        $res = $data;
        foreach ($attrs as $attr) {
            if (isset($res[$attr])) {
                $res = $res[$attr];
            } else {
                return '';
            }
        }

        return $res;
    }

    public function getWebhook(Webhook $webhook) {
        return response()->json($webhook);
    }

    public function getWebhooks() {
        return response()->json(Webhook::orderBy('name', 'asc')->get());
    }
}
