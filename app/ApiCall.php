<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    protected $fillable = [
        'api_endpoint_id', 'event_id', 'product_id', 'transaction_code', 'request', 'response', 'success'
    ];

    public function api_endpoint() {
        return $this->belongsTo(ApiEndpoint::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
