<?php

namespace App\Listeners;

use App\Action;
use App\Api;
use App\ApiCall;
use App\ApiEndpoint;
use App\ApiRequest;
use App\ApiResponse;
use App\Event;
use App\Events\BilletPaid;
use App\Events\NewApiRequest;
use App\Events\NewApiResponse;
use App\Events\NewBillet;
use App\Events\NewWebhookCall;
use App\Integration;
use App\Jobs\ProcessApiCall;
use App\Product;
use App\Webhook;
use App\WebhookCall;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use stdClass;

use function GuzzleHttp\json_decode;

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

        // obter a webhook com todos os eventos cadastrados para esta chamada,
        // que tenham uma ação com um produto relacionado.
        /* $webhook = Webhook::with(['events' => function($q) use ($event){
                $q->whereHas('actions', function($query) use ($event) {
                    $query->where('product_id', $event->product->id);
                });
            }])->find($event->webhookCall->webhook_id); */

        $webhook = Webhook::with('events')->find($event->webhookCall->webhook_id);

        //verifica se existe algum evento a ser tratado por esta chamada
        foreach ($webhook->events as $callEvent) {
            /* nenhum evento é disparado até que as condições sejam checadas */
            $dispatch = false;

            /* obtem as condições para disparo do evento e processa cada uma */
            $conditions = json_decode($callEvent->conditions);
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
                /* verifica associação de eventos internos e dispara caso existam */
                $this->dispachSystemEvents($callEvent, $event->webhookCall, $event->product);

                /* se todas as condições foram satisfeitas, processa o disparo do evento */
                $this->callActionsForEvent($webhook, $event->webhookCall, $callEvent, $event->product);
            } else {
                /* caso algum condição não tenha sido satisfeita, o evento não é disparado */
            }

            //reinicia a variável dispatch
            $dispatch = false;
        }
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

    public function callActionsForEvent(Webhook $webhook, WebhookCall $webhookCall, Event $event, Product $product) {
        $actions = $event->actions()->where('product_id', $product->id)->Active()->get();

        foreach ($actions as $action) {

            $endpoint = ApiEndpoint::with('api')->find($action->api_endpoint_id);
            $reqFields = json_decode($endpoint->json, true);
            $variables = $this->loadVars($webhook, $endpoint->api, $webhookCall, $action);

            $req = array();
            foreach ($reqFields as $reqField) {
                $req[$reqField['name']] = $this->replaceVar($this->replaceVar($reqField['value'], $variables), $variables);

            }

            /* Antes de continuar o processamento, verifica por entradas de
            chamadas a API já feitas para o mesmo endpoint, pelo mesno evento,
            com o mesmo produto e o mesmo código de transacao. Caso não exista
            nenhum registro processe para as chamadas a API */
            if (ApiCall::where('api_endpoint_id', $endpoint->id)
                ->where('event_id', $event->id)
                ->where('product_id', $product->id)
                ->where('transaction_code', $webhookCall->transaction_code)
                ->doesntExist()) {

                $apiCall = New ApiCall([
                    'api_endpoint_id' => $endpoint->id,
                    'event_id' => $event->id,
                    'product_id' => $product->id,
                    'transaction_code' => $webhookCall->transaction_code,
                    'request' => json_encode($req)
                ]);

                $apiCall->save();

                $integration = new Integration([
                    'webhook_call_id' => $webhookCall->id,
                    'event_id' => $event->id,
                    'action_id' => $action->id,
                    'api_call_id' => $apiCall->id
                ]);

                $integration->save();

                ProcessApiCall::dispatch($apiCall, $endpoint, $req)->delay(now()->addMinutes($action->delay));
            }
        }
    }

    private function replaceVar($data, $variables) {
        $regex = '~\{([\s\w]*)\}~';
        return preg_replace_callback($regex, function($match) use ($variables) {
            return $variables[trim($match[1])];
        }, $data);
    }

    /**
     * Carrega variáveis para substituição de valores
     *
     * @param Webhook $webhook
     * @param Api $api
     * @param WebhookCall $webhookCall
     * @param Action $action
     * @return void
     */
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

    /**
     * Dispara eventos para controle de boletos, internamente
     *
     * @param Event $event
     * @param WebhookCall $webhookCall
     * @param Product $product
     * @return void
     */
    public function dispachSystemEvents(Event $event, WebhookCall $webhookCall, Product $product) {
        switch ($event->trigger_system_event) {
            case 'billet_pending':
                // Novo boleto gerado aguardando pagamento
                event(new NewBillet($webhookCall, $product));
                break;

            case 'billet_paid':
                // Boleto pago
                event(new BilletPaid($webhookCall));
                break;

            default:
                // Não há eventos de sistema vinculados, nada a fazer
                break;
        }
    }
}
