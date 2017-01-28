<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Ingredient;
use Kalnoy\Nestedset\NodeTrait;

class IngredientSaved
{
    use InteractsWithSockets, SerializesModels;
    use NodeTrait;

    public $ingredient;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }
}
