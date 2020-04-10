<?php

namespace App\Events;

use App\Product;
use App\WebhookCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBillet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $webhookCall;
    public $product;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall, Product $product)
    {
        $this->webhookCall = $webhookCall;
        $this->product = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
