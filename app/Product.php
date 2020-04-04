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

    public function actions() {
        return $this->hasMany(Action::class);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('name', 'asc');
    }
}
