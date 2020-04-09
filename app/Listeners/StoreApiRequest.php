<?php

namespace App\Listeners;

use App\Events\NewApiRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreApiRequest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewApiRequest  $event
     * @return void
     */
    public function handle(NewApiRequest $event)
    {
        $event->apiCall->save();
    }
}
