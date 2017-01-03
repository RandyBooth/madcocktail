<?php

namespace App;

use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Glass extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['deleted_at'];
    protected $fillable  = ['title'];
    protected $hidden = ['id'];
    protected $table = 'glasses';

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
                'unique' => true,
            ]
        ];
    }

    public function recipes()
    {
        return $this->hasMany('App\Recipe');
    }
}
