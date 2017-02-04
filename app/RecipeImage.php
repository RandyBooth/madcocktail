<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeImage extends Model
{
    protected $fillable = ['recipe_id', 'image'];
    protected $table = 'recipe_images';
}
