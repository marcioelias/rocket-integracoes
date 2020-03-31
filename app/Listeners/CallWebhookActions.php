<?php

namespace App\Listeners;

use App\Events\NewWebhookCall;
use App\Webhook;
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
                Log::info($event->name);
                Log::info('Condição verdadeira');
            } else {
                /* caso algum condição não tenha sido satisfeita, o evento não é disparado */
                Log::info($event->name);
                Log::info('Condição falsa');
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
}
