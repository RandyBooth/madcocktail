<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\OAuth;
use App\User;
use Auth;
use Hash;
use Socialite;
use Jrean\UserVerification\Facades\UserVerification;

class OAuthController extends Controller
{
    protected $provider = '';
    protected $provider_list = ['facebook', 'google', 'twitter'];

    public function redirect($provider)
    {
        if ($this->validProvider($provider)) {
            return Socialite::driver($this->provider)->redirect();
        }
    }

    public function callback($provider)
    {
        if ($this->validProvider($provider)) {
            if (Auth::check()) {
                Auth::logout();
            }

            $social_user = Socialite::driver($this->provider)->user();

            if ( !empty($social_user->id)) {
                $oauth = OAuth::where('provider', '=', $this->provider)->where('provider_uid', '=', $social_user->id)->first();

                if ($oauth) {
                    $user = User::find($oauth->user_id);
                } else {
                    $user = User::where('email', $social_user->email)->first();
                }

                if ($user) {
                    $oauth = OAuth::firstOrCreate([
                        'user_id' => $user->id,
                        'provider' => $this->provider,
                        'provider_uid' => $social_user->id
                    ]);

                    Auth::login($user);

                    return redirect('/');
                } else {
                    if (!empty($social_user->email)) {
                        $user           = new User;
                        $user->email    = $social_user->email;
                        $user->password = Hash::make(str_random(60));

                        if ($user->save()) {
                            $oauth = OAuth::firstOrCreate([
                                'user_id' => $user->id,
                                'provider' => $this->provider,
                                'provider_uid' => $social_user->id
                            ]);

                            UserVerification::generate($user);
                            UserVerification::sendQueue($user, 'Please Confirm Your Email');

                            Auth::login($user);

                            // redirect the user and suggest changing their password
//                            return redirect('password/reset')->with('success', 'Confirm email sent!');
                            return redirect('/')->with('success', 'Confirm email sent!');
                        }
                    }
                }
            }
        }
    }

    private function validProvider($provider)
    {
        if (in_array($provider, $this->provider_list)) {
            $this->provider = $provider;
            return true;
        }

        abort(404);
    }
}
