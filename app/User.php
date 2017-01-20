<?php

namespace App;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = ['email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
    }

    public function getRoleTypeAttribute() {
        switch($this->role) {
            case 1:
                $type = 'Admin';
                break;
            default:
                $type = 'User';
                break;
        }

        return $type;
    }
}
