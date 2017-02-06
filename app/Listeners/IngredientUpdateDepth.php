<?php

namespace App\Listeners;

use App\Events\IngredientSaved;
use App\Ingredient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
                $ingredient_depth = Ingredient::withDepth()->find($ingredient->id);

                if ($ingredient_depth) {
                    $ingredient_depth->level = $ingredient_depth->depth;
                    $ingredient_depth->save();
                }
            }
        }
    }
}
