<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeCount extends Model
{
    protected $hidden = ['id', 'recipe_id'];
}
