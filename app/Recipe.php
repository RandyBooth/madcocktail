<?php

namespace App;

use App\Helpers\Helper;
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
    protected $fillable = ['title', 'description', 'directions', 'glass_id', 'user_id', 'is_active'];
    protected $hidden = ['id'];
    protected $table = 'recipes';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope);

        static::saving(function ($model) {
            $model->title_first_letter = strtolower(substr(preg_replace('/[^a-zA-Z0-9]+/', '', $model->title), 0, 1));
        });

        static::created(function ($model) {
            if ($model->id && empty($model->token)) {
                $recipe_id = $model->id;
                $token_valid = false;

                do {
                    $token = Helper::hashids_random($recipe_id);

                    if (!empty($token)) {
                        $recipe = self::token($token)->first();

                        if (!$recipe) {
                            $model->token = $token;

                            if ($model->save()) {
                                $token_valid = true;
                            }
                        }
                    }
                } while(!$token_valid);
            }
        });
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

    public function setTitleSupAttribute()
    {
        if (!empty($this->title)) {
            return Helper::html_sup($this->title);
        }
    }

    public function getTitleSupAttribute()
    {
        if (!empty($this->title)) {
            return Helper::html_sup($this->title);
        }
    }

    public function scopeToken($query, $type)
    {
        return $query->where('token', 'LIKE BINARY', $type);
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
