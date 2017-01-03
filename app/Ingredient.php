<?php

namespace App;

use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Support\Facades\Cache;
use Kalnoy\Nestedset\NodeTrait;
use Vinkla\Hashids\Facades\Hashids;

class Ingredient extends Model
{
    use NodeTrait;
    use SoftDeletes;
    use Sluggable;

    protected $appends = ['token'];
    protected $dates = ['deleted_at'];
    protected $fillable = ['title'];
    protected $hidden = ['id'];
    protected $table = 'ingredients';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => false,
            ]
        ];
    }

    public function getTokenAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function scopeIsAlcoholic($query)
    {
        return $query->where('is_alcoholic', '=', 1);
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Recipe')->orderBy('view_count', 'DESC')->orderBy('title');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists) {
        if ($parent instanceof Recipe) {
            return new IngredientRecipe($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
