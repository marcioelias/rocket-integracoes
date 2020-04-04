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

    public function actions() {
        return $this->hasMany(Action::class);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('name', 'asc');
    }

    public function scopeNotSystemEvent($query) {
        return $query->where('system_event', false);
    }

    public function scopeSystemEvent($query) {
        return $query->where('system_event', true);
    }
 }
