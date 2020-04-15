<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'name', 'product_id', 'event_id', 'api_endpoint_id', 'delay', 'data', 'active', 'trigger_data'
    ];

    public function scopeActive($query) {
        return $query->where('active', true);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function api_endpoint() {
        return $this->belongsTo(ApiEndpoint::class);
    }

    public function integrations() {
        return $this->hasMany(Integration::class);
    }
}
