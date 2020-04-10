<?php

namespace App\Listeners;

use App\Billet;
use App\Events\NewBillet;
use App\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_decode;

class StoreBillet
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
     * @param  NewBillet  $event
     * @return void
     */
    public function handle(NewBillet $event)
    {
        try {
            $data = json_decode($event->webhookCall->mapped_data, true);

            Billet::firstOrCreate(
                [
                    'billet_number' => $data['billet_number'],
                    'transaction_code' => $data['webhook_transaction_code'],
                ],
                [
                    'url' => $data['billet_url'],
                    'amount' => $data['sale_amount'],
                    'expiration_date' => $data['billet_expiration_date'],
                    'billet_status' => 'pending',
                    'webhook_call_id' => $event->webhookCall->id,
                    'product_id' => $event->product->id
                ]);

        } catch (\Exception $e) {
            Log::emergency($e);
        }
    }
}
