<?php

namespace App;

use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

class Recipe extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $appends = ['token'];
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'description', 'direction', 'glass_id', 'view_count', 'user_id', 'is_active'];
    protected $hidden = ['id'];
    protected $table = 'recipes';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

    protected $casts = [
        'direction' => 'boolean',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
            ]
        ];
    }

    public function getTokenAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function ingredients()
    {
        return $this
            ->belongsToMany('App\Ingredient')
            ->withPivot('measure_amount')
            ->join('measures', 'ingredient_recipe.measure_id', '=', 'measures.id')
            ->select('ingredients.*', 'measures.title AS pivot_measure_title')
//            ->whereNull('category_recipe.deleted_at')
//            ->withTimestamps()
            ->orderBy('order_by');
    }

    public function glass()
    {
        return $this->belongsTo('App\Glass');
    }

    public function photos()
    {
        return $this->morphMany('App\Photo', 'imageable');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Ingredient) {
            return new IngredientRecipe($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
