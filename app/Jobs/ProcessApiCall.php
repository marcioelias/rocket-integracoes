<?php

namespace App\Jobs;

use App\ApiCall;
use App\ApiEndpoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessApiCall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $endpoint;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ApiEndpoint $endpoint, array $data)
    {
        $this->endpoint = $endpoint;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('apiCall')->allow(1)->every(10)->block(10)->then(function () {
            info('Lock obtained...');

            // Handle job...
            Log::info('rodando o job...');
            $apiCall = New ApiCall([
                'api_endpoint_id' => $this->endpoint->id,
                'request' => json_encode($this->data)
            ]);

            $response = $this->callApi($this->endpoint, $this->data);

            $apiCall->response = json_encode($response->json());
            $apiCall->success = $this->successfulResponse($this->endpoint, $response);

        }, function () {
            // Could not obtain lock...

            return $this->release(5);
        });
    }


    /**
     * Efetua uma chamada a uma API externa, utilizando
     * o Http Client do Laravel.
     *
     * @param ApiEndpoint $endpoint
     * @param array $data
     * @return Response
     */
    private function callApi(ApiEndpoint $endpoint, array $data) {
        $url = $endpoint->api->base_url . '/' . $endpoint->relative_url;
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
        return $response;
    }

    /**
     * Procura por um campo/valor na resposta da chamada para validar
     * seu retorno. Este campo/valor sõa configurados no cadastro da API.
     *
     * @param ApiEndpoint $apiEndpoint
     * @param Response $response
     * @return boolean
     */
    private function successfulResponse(ApiEndpoint $apiEndpoint, Response $response) {
        $field = $apiEndpoint->field_ok;
        $value = $apiEndpoint->code_ok;

        $val = array_filter(json_decode($response, true), function ($val, $key) use ($field, $value) {
            return ($key == $field && $val == $value);
        }, ARRAY_FILTER_USE_BOTH);

        return count($val) > 0;
    }
}
