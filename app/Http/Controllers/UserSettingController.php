<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Validator;
use Jrean\UserVerification\Facades\UserVerification;

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
            'display_name' => 'nullable|min:3|display_name',
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
            $user->display_name = $data['display_name'];
            $user->username = $data['username'];
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

    private function clear($user = null)
    {
        if ($user) {
            Cache::forget('user_ID_'.$user->id);
            Cache::forget('user_EMAIL_'.$user->email);
            Cache::forget('user_USERNAME_'.$user->username);
        }
    }
}
