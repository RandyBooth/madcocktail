<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OAuth extends Model
{
    protected $fillable = ['user_id', 'provider', 'provider_uid', 'token'];
    protected $table    = 'oauth';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
