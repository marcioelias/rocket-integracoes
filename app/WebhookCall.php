<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookCall extends Model
{
    protected $fillable = [
        'webhook_id', 'data', 'mapped_data', 'transaction_code'
    ];

    protected function webhook() {
        return $this->belongsTo(Webhook::class);
    }
}
