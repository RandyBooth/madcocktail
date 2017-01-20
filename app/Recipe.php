<?php

namespace App;

use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $appends = ['title_sup'];
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
        'directions' => 'array',
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

    public function getTitleSupAttribute()
    {
        if (!empty($this->title)) {
            return preg_replace("/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/", "<sup>$1</sup>", $this->title);
        }
    }

    public function ingredients()
    {
        return $this
            ->belongsToMany('App\Ingredient')
//            ->ancestors()
            ->withoutGlobalScope(ActiveScope::class)
            ->withPivot('measure_amount')
            ->join('measures', 'ingredient_recipe.measure_id', '=', 'measures.id')
            ->select('ingredients.*', 'measures.title AS pivot_measure_title')
            ->orderBy('order_by')
            ->orderBy('title')
            ;
    }

    public function counts()
    {
        return $this->hasOne('App\RecipeCount');
    }

    public function glass()
    {
        return $this->belongsTo('App\Glass');
    }

//    public function photos()
//    {
//        return $this->morphMany('App\Photo', 'imageable');
//    }

//    public function reviews()
//    {
//        return $this->hasMany('App\Review');
//    }

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Ingredient) {
            return new IngredientRecipe($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
