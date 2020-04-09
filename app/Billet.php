<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    protected $fillable = [
        'billet_number', 'url', 'amount', 'expiration_date', 'webhook_call_id', 'product_id', 'transaction_code', 'date_approved'
    ];

    public function webhook_call() {
        return $this->belongsTo(WebhookCall::class);
    }

    public function scopeExpirated($query, $days) {
        $dt = (new \DateTime())->modify("-$days days");
        return $query->where('expiration_date' <= $dt);
    }
}
