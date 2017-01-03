<?php

namespace App;

use App\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occasion extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['deleted_at'];
    protected $fillable  = ['title'];
    protected $hidden = ['id'];
    protected $table = 'occasions';

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
}
