<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeImage extends Model
{
    protected $fillable = ['recipe_id', 'image'];
    protected $table = 'recipe_images';

    public function scopeImage($query, $id)
    {
        return $query->select('image')->where('recipe_id', $id)->first();
    }
}
