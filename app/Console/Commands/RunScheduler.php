<?php

namespace App\Console\Commands;

use App\Action;
use App\Api;
use App\ApiCall;
use App\ApiEndpoint;
use App\Billet;
use App\Jobs\ProcessApiCall;
use App\Webhook;
use App\WebhookCall;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to be running at cron jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('Running command: cron:run');

        /* obtem todas as ações que devem notificar eventos do sistema (vencimento de boleto) */
        $actions = Action::with('product')
            ->whereHas('event', function($q) {
                $q->where('system_event',true);
            })->get();

        foreach ($actions as $action) {
            $dt_modify = $action->trigger_data * -1;
            $data_inicio = (new DateTime())->modify($dt_modify.' days')->setTime(0, 0, 0);
            $data_termino = (new DateTime())->modify($dt_modify.' days')->setTime(23, 59, 59);

            $boletos = Billet::where('product_id', $action->product_id)
                ->whereBetween('expiration_date', [$data_inicio, $data_termino])
                ->where('billet_status', 'pending')
                ->get();

            foreach ($boletos as $boleto) {
                $endpoint = ApiEndpoint::with('api')->find($action->api_endpoint_id);
                $reqFields = json_decode($endpoint->json, true);
                $webhookCall = WebhookCall::find($boleto->webhook_call_id);
                $webhook = Webhook::find($webhookCall->webhook_id);
                $webhook = $webhookCall->webhook;

                $variables = $this->loadVars($webhook, $endpoint->api, $webhookCall, $action);

                $req = array();
                foreach ($reqFields as $reqField) {
                    $req[$reqField['name']] = $this->replaceVar($this->replaceVar($reqField['value'], $variables), $variables);

                }

                $apiCall = New ApiCall([
                    'api_endpoint_id' => $endpoint->id,
                    'request' => json_encode($req)
                ]);

                $apiCall->save();

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
