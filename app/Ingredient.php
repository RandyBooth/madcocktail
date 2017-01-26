<?php

namespace App;

use App\Helpers\Helper;
use App\RecipeCount;
use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Vinkla\Hashids\Facades\Hashids;

class Ingredient extends Model
{
    use NodeTrait;
    use SoftDeletes;
    use Sluggable;

    protected $appends = ['title_sup'];
//    protected $casts = ['id' => 'integer'];
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'parent_id', 'level', 'is_alcoholic', 'is_active', 'user_id'];
    protected $hidden = ['id'];
    protected $table = 'ingredients';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
        static::created(function ($model) {
            if ($model->id && empty($model->token)) {
                $ingredent_id = $model->id;
                $token_valid = false;

                do {
                    $token = Hashids::encode(mt_rand(100000,999999).$ingredent_id);

                    if (!empty($token)) {
                        $ingredent = Ingredient::where('token', $token)->first();

                        if (!$ingredent) {
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
            return Helper::html_sup($this->title);
        }
    }

    public function scopeToken($query, $type)
    {
        return $query->where('token', $type);
    }

    public function scopeIsAlcoholic($query)
    {
        return $query->where('is_alcoholic', 1);
    }

    public function recipes()
    {
//        return $this->belongsToMany('App\Recipe')->orderBy('view_count', 'DESC')->orderBy('title');
    }

    /*public function recipes()
    {
        $table_recipe_count = with(new RecipeCount)->getTable();
        return $this->belongsToMany('App\Recipe')->join($table_recipe_count, $table_recipe_count.'.id', '=', 'recipes.id')->orderBy('title');
    }*/

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Recipe) {
            return new IngredientRecipe($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
