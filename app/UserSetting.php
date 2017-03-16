<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $casts = ['about' => 'array'];
    protected $fillable = ['about', 'link', 'facebook_link', 'google_plus_link', 'pinterest_link', 'twitter_link'];
    protected $table = 'user_settings';
}
