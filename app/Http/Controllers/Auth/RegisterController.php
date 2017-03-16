<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HelperImage;
use App\Http\Controllers\Controller;
use App\User;
use File;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Image;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest'], ['except' => ['getVerification', 'getVerificationError']]);
        $this->middleware(['xss', 'isVerified'], ['only' => ['register']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|min:3|max:255|least_one_letter|alpha_dash|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'birth' => 'date_format:Y-m-d|over_age:21',
            'month' => 'required|digits:2',
            'day' => 'required|digits:2',
            'year' => 'required|digits:4',
            'first_name' => 'honeypot',
            'my_time' => 'required|honeytime:2',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birth' => $data['birth'],
        ]);
    }

    /**
    * Handle a registration request for the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        $data = $request->all();
        $data['birth'] = '';

        if (!empty($data['month']) && !empty($data['day']) && !empty($data['year'])) {
            $data['birth'] = $data['year'].'-'.$data['month'].'-'.$data['day'];
        }

        $this->validator($data)->validate();
        $user = $this->create($data);
        event(new Registered($user));

        if ($user) {
//            $this->guard()->login($user);

            $image = HelperImage::get_gravatar($data['email']);

            if (!empty($image)) {
                HelperImage::upload_user_image($image, $user);
            }

            UserVerification::emailView(new \App\Mail\SendConfirmMail($user));
            UserVerification::generate($user);
            UserVerification::sendQueue($user);

            return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('success', 'You received an email for confirming your registration. Please check your email.');
        }

        return false;
    }
}
