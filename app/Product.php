<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code', 'name', 'webhook_id'
    ];

    public function webhook() {
        return $this->belongsTo(Webhook::class);
    }
}
