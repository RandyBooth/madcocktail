<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jrean\UserVerification\Traits\UserVerification;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use UserVerification;

    protected $fillable = ['username', 'email', 'password', 'birth'];
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
