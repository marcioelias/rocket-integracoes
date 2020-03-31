<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'conditions', 'webhook_id'
    ];

    public function webhook() {
        return $this->belongsTo(Webhook::class);
    }
}
