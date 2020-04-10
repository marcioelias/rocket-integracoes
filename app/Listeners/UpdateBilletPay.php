<?php

namespace App\Listeners;

use App\Billet;
use App\Events\BilletPaid;
use App\WebhookCall;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateBilletPay
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
     * @param  BilletPaid  $event
     * @return void
     */
    public function handle(BilletPaid $event)
    {
        try {
            $data = json_decode($event->webhookCall->mapped_data, true);

            $billet = Billet::where('transaction_code', $data['webhook_transaction_code'])
                ->where('billet_status', 'pending')
                ->first();

            if ($billet) {
                $billet->billet_status = 'paid';
                $billet->date_approved = $data['date_approved'];
                $billet->save();
            } else {
                Log::info('Boleto não encontrado. Transacao: '.$data['webhook_transaction_code']);
            }


        } catch (\Exception $e) {
            Log::emergency($e);
        }
    }
}
