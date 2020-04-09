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

            /* Se o produto for novo, cadastra, caso contrário obterm o ID do produto existente
            para vincular no boleto */
            $product = Product::firstOrCreate(
                ['product_code' => $data['product_code'], 'webhook_id' => $event->webhookCall->webhook_id],
                ['name' => $data['product_name']]
            );

            $billet = new Billet([
                'billet_number' => $data['billet_number'],
                'url' => $data['billet_url'],
                'amount' => $data['sale_amount'],
                'expiration_date' => $data['billet_expiration_date'],
                'billet_status' => 'pending',
                'webhook_call_id' => $event->webhookCall->id,
                'transaction_code' => $data['webhook_transaction_code'],
                'product_id' => $product->id
            ]);

            $billet->save();
        } catch (\Exception $e) {
            Log::emergency($e);
        }
    }
}
