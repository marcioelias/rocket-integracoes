<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookCall extends Model
{
    protected $fillable = [
        'webhook_id', 'data', 'mapped_data'
    ];

    protected function webhook() {
        return $this->belongsTo(Webhook::class);
    }
}
