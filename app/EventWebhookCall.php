<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventWebhookCall extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
