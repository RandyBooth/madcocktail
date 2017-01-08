<?php

namespace App\Listeners;

use App\Events\IngredientSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ingredient;

class IngredientUpdateDepth
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
     * @param  IngredientSaved  $event
     * @return void
     */
    public function handle(IngredientSaved $event)
    {
        if (!empty($event->ingredient)) {
            $ingredient = $event->ingredient;

            if ($ingredient->id && $ingredient->_lft) {
                $ingredient_depth = $ingredient->withDepth()->find($ingredient->id);
                $ingredient_depth->level = $ingredient_depth->depth;
                $ingredient_depth->save();
            }
        }
    }
}
