<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    public $fillable = [
        'name', 'relative_url', 'token', 'json', 'json_mapping'
    ];

    public function scopeEmOrdem($query) {
        return $query->orderBy('name', 'asc');
    }

    public function webhookCalls() {
        return $this->hasMany(WebhookCall::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function events() {
        return $this->hasMany(Event::class);
    }
}
