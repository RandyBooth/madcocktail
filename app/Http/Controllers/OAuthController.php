<?php

namespace App\Http\Controllers;

use App\Helpers\HelperImage;
use App\OAuth;
use App\User;
use Auth;
use Cache;
use File;
use Hash;
use Helper;
use Image;
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
                    $user = Cache::remember('user_ID_'.$oauth->user_id, 1440, function () use ($oauth) {
                        return User::find($oauth->user_id);
                    });
                } else {
                    $user = Cache::remember('user_EMAIL_'.strtolower($social_user->email), 1440, function () use ($social_user) {
                        return User::where('email', $social_user->email)->first();
                    });
                }

                $provider = $this->provider;
                $social_user_id = $social_user->id;

                if ($user) {
                    OAuth::firstOrCreate([
                        'user_id' => $user->id,
                        'provider' => $provider,
                        'provider_uid' => $social_user_id
                    ]);

                    Auth::login($user);

                    return redirect('/');
                } else {
                    if (!empty($social_user->email)) {
                        $user           = new User;
                        $user->email    = $social_user->email;
                        $user->password = Hash::make(str_random(60));

                        if ($user->save()) {
                            OAuth::firstOrCreate([
                                'user_id' => $user->id,
                                'provider' => $provider,
                                'provider_uid' => $social_user_id
                            ]);

                            $image = (!empty($social_user->avatar_original))
                                ? $social_user->avatar_original
                                : HelperImage::get_gravatar($social_user->email);

                            HelperImage::upload_user_image($image, $user);

                            UserVerification::emailView(new \App\Mail\SendConfirmMail($user));
                            UserVerification::generate($user);
                            UserVerification::sendQueue($user);

                            Auth::login($user);

                            return redirect()->route('user-settings.index.edit')->with('success', 'You received an email for confirming your registration. Please check your email.');
                        }
                    }
                }
            }
        }

        abort(404);
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
