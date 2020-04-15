<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'webhook_call_id', 'event_id', 'action_id', 'api_call_id'
    ];

    public function webhook_call() {
        return $this->belongsTo(WebhookCall::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function action() {
        return $this->belongsTo(Action::class);
    }

    public function api_call() {
        return $this->belongsTo(ApiCall::class);
    }
}
