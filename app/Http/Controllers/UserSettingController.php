<?php

namespace App\Http\Controllers;

use Auth;
use Cache;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Jrean\UserVerification\Facades\UserVerification;
use Validator;

class UserSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function indexEdit()
    {
        $user = Auth::user();

        return view('user-settings.index', compact('user'));
    }

    public function indexUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $data['birth'] = '';

        if (!empty($data['month']) && !empty($data['day']) && !empty($data['year'])) {
            $data['birth'] = $data['year'].'-'.$data['month'].'-'.$data['day'];
        }

        $validator = Validator::make($data, [
            'username' => [
                'required', 'min:3', 'max:255', 'least_one_letter', 'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'birth' => 'date_format:Y-m-d|over_age:21',
            'month' => 'required|digits:2',
            'day' => 'required|digits:2',
            'year' => 'required|digits:4',
            'first_name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            if (empty($user->username) || Helper::is_admin()) {
                $user->username = $data['username'];
            }

            $user->birth = $data['birth'];

            if ($user->save()) {
                $this->clear($user);
                return redirect()->route('user-settings.index.edit')->with('success', 'Settings has been updated successfully.');
            }
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Settings update fail.');
    }

    public function emailEdit()
    {
        $user = Auth::user();
        $email = $user->email;

        return view('user-settings.email', compact('email'));
    }

    public function emailUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => [
                'required','email','max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'first_name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            $old_email = $user->email;
            $user->email = $data['email'];

            if ($user->save()) {
                $this->clear($user);

                if (strtolower($old_email) != strtolower($data['email'])) {
                    UserVerification::emailView(new \App\Mail\SendConfirmMail($user));
                    UserVerification::generate($user);
                    UserVerification::sendQueue($user);
                }

                return redirect()->route('user-settings.email.edit')->with('success', 'E-mail address has been updated successfully.');
            }
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'E-mail address update fail.');
    }

    public function passwordEdit()
    {
        return view('user-settings.password');
    }

    public function passwordUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'password_current' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            if (\Hash::check($data['password_current'], $user->password)) {
                $user->password = bcrypt($data['password']);

                if ($user->save()) {
                    return redirect()->route('user-settings.password.edit')->with('success', 'Password has been updated successfully.');
                }
            } else {
                return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Current password is wrong.');
            }
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Password update fail.');
    }

    public function profileEdit()
    {
        $user = Auth::user();
        $user_settings = $user->settings()->firstOrCreate([]);

        if (is_array($user_settings->about)) {
            $user_settings->about = implode("\n\n", $user_settings->about);
        }

        return view('user-settings.profile', compact('user', 'user_settings'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $user_settings = $user->settings()->firstOrFail();
        $data = $request->all();

        $validator = Validator::make($data, [
            'display_name' => 'present|nullable|min:3|display_name',
            'about' => 'present|nullable',
            'link' => 'present|nullable|url|active_url',
            'facebook_link' => 'present|nullable|url|active_url|domain_contains:facebook.com',
            'google_plus_link' => 'present|nullable|url|active_url|domain_contains:plus.google.com,false',
            'pinterest_link' => 'present|nullable|url|active_url|domain_contains:pinterest.com',
            'twitter_link' => 'present|nullable|url|active_url|domain_contains:twitter.com',
            'first_name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            $user->display_name = $data['display_name'];
            $user_settings->about = Helper::textarea_to_array($data['about']);
            $user_settings->link = $data['link'];
            $user_settings->facebook_link= $data['facebook_link'];
            $user_settings->google_plus_link= $data['google_plus_link'];
            $user_settings->pinterest_link= $data['pinterest_link'];
            $user_settings->twitter_link= $data['twitter_link'];

            if ($user->save()) {
                if ($user_settings->save()) {
                    $this->clear($user, true);
                    return redirect()->route('user-settings.profile.edit')->with('success', 'Profile setting has been updated successfully.');
                }

                $this->clear($user);
            }
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Profile setting update fail.');
    }

    public function resendVerification()
    {
        $user = Auth::user();

        if (!$user->verified) {
            UserVerification::emailView(new \App\Mail\SendConfirmMail($user));
            UserVerification::generate($user);
            UserVerification::sendQueue($user);

            return redirect()->route('home')->with('success', 'You received an email for confirming your registration. Please check your email.');
        }

        abort(404);
    }

    private function clear($user = null, $settings = false)
    {
        if ($user) {
            Cache::forget('user_ID_'.$user->id);
            Cache::forget('user_EMAIL_'.strtolower($user->email));
            Cache::forget('user_USERNAME_'.strtolower($user->username));

            if ($settings) {
                Cache::forget('usersettings_ID_'.$user->id);
            }
        }
    }
}
