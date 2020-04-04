<?php

namespace App\Listeners;

use App\Action;
use App\Api;
use App\ApiEndpoint;
use App\Event;
use App\Events\NewWebhookCall;
use App\Webhook;
use App\WebhookCall;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use stdClass;

class CallWebhookActions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewWebhookCall  $event
     * @return void
     */
    public function handle(NewWebhookCall $event)
    {
        $call = json_decode(json_encode($event->webhookCall));
        $webhookCall = $event->webhookCall;

        //obter a webhook com todos os eventos cadastrados para esta chamada
        $webhook = Webhook::with('events')->find($event->webhookCall->webhook_id);

        //verifica se existe algum evento a ser tratado por esta chamada
        foreach ($webhook->events as $event) {
            /* nenhum evento é disparado até que as condições sejam checadas */
            $dispatch = false;

            /* obtem as condições para disparo do evento e processa cada uma */
            $conditions = json_decode($event->conditions);
            foreach ($conditions as $condition) {
                /* se o evento tiver mais de uma condição a satisfazer, considera o agrupamento por AND ou OR */
                if (is_object($condition->logic)) {
                    if ($condition->logic->value == 'and') {
                        $dispatch = $dispatch && $this->conditionIsTrue($condition, json_decode($call->data));
                    } else {
                        $dispatch = $dispatch || $this->conditionIsTrue($condition, json_decode($call->data));
                    }
                } else {
                    /* caso tenha uma única condição, o resultado da mesma será avaliado */
                    $dispatch = $this->conditionIsTrue($condition, json_decode($call->data));
                }
            }

            if ($dispatch) {
                /* se todas as condições foram satisfeitas, processa o disparo do evento */
                //dispara o evento
                //Log::info($event->name);
                //Log::info('Condição verdadeira');
                $this->callActionsForEvent($webhook, $webhookCall, $event);
            } else {
                /* caso algum condição não tenha sido satisfeita, o evento não é disparado */
                //Log::info($event->name);
                //Log::info('Condição falsa');
            }

            //reinicia a variável dispatch
            $dispatch = false;
        }

        //Log::debug($event->webhookCall);

    }

    public function conditionIsTrue(stdClass $condition, stdClass $data) {
        $value1 = $this->parseStdAttr($data, $condition->field);
        $value2 = $condition->value;
        switch ($condition->operation->value) {
            /* igual */
            case 'equal':
                return $value1 == $value2;
                break;

            /* diferente */
            case 'not-equal':
                return $value1 != $value2;
                break;

            /* maior que */
            case 'greater-than':
                return $value1 > $value2;
                break;

            /* maior ou igual */
            case 'greater-than-equal':
                return $value1 >= $value2;
                break;

            /* menor que */
            case 'less-than':
                return $value1 < $value2;
                break;

            /* menor ou igual */
            case 'less-than-equal':
                return $value1 <= $value2;
                break;
        }
    }
    private function parseStdAttr(stdClass $data, string $stdAttr) {
        $attrs = explode('.', $stdAttr);
        $res = json_decode(json_encode($data), true);
        foreach ($attrs as $attr) {
            $res = $res[$attr];
        }
        return $res;
    }

    public function callActionsForEvent(Webhook $webhook, WebhookCall $webhookCall, Event $event) {
        $actions = $event->actions()->Active()->get();
        foreach ($actions as $action) {
            $endpoint = ApiEndpoint::with('api')->find($action->api_endpoint_id);
            $reqFields = json_decode($endpoint->json, true);
            //Log::debug($reqFields);
            $variables = $this->loadVars($webhook, $endpoint->api, $webhookCall, $action);

            $req = array();
            foreach ($reqFields as $reqField) {
                $req[$reqField['name']] = $this->replaceVar($this->replaceVar($reqField['value'], $variables), $variables);

            }

            $this->callApi($endpoint, $req);
        }
    }

    private function callApi(ApiEndpoint $endpoint, array $data) {
        $url = $endpoint->api->base_url . '/' . $endpoint->relative_url;
        Log::debug($url);
        Log::debug($data);
        switch ($endpoint->method) {
            case 'GET':
                $response = Http::get($url, $data);
                break;

            case 'POST':
                $response = Http::post($url, $data);
                break;

            case 'PUT':
                $response = Http::put($url, $data);
                break;

            case 'PATCH':
                $response = Http::patch($url, $data);
                break;

            case 'DELETE':
                $response = Http::delete($url, $data);
                break;
        }
        Log::debug($response);
    }

    private function replaceVar($data, $variables) {
        $regex = '~\{([\s\w]*)\}~';
        return preg_replace_callback($regex, function($match) use ($variables) {
            return $variables[trim($match[1])];
        }, $data);
    }

    private function loadVars(Webhook $webhook, Api $api, WebhookCall $webhookCall, Action $action) {
        $result['webhook_name'] = $webhook->name;
        $result['webhook_url'] = $webhook->relative_url;
        $result['webhook_token'] = $webhook->token;
        $result['api_name'] = $api->name;
        $result['api_base_url'] = $api->base_url;
        $result['api_token'] = $api->token;
        $result['api_username'] = $api->username;
        $result['api_password'] = $api->password;
        foreach (json_decode($webhookCall->mapped_data, true) as $key => $value) {
            $result[$key] = $value;
        }
        foreach (json_decode($action->data, true) as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }
}
