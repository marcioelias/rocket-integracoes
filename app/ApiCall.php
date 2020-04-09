<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    protected $fillable = [
        'api_endpoint_id', 'request', 'response', 'success'
    ];

    public function api_endpoint() {
        return $this->belongsTo(ApiEndpoint::class);
    }
}
