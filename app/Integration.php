<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'name', 'api_endpoint_id', 'webhook_id', 'field_mapping'
    ];

    public function apiEndpoint() {
        return $this->belongsTo(ApiEndpoint::class);
    }

    public function webhook() {
        return $this->belongsTo(Webhook::class);
    }
}
