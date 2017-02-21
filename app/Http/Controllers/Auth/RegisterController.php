<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

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
            'username' => 'required|min:3|max:255|alpha_dash|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'birth' => 'filled|date_format:Y-m-d|over_age:21',
            'month' => 'required|digits:2',
            'day' => 'required|digits:2',
            'year' => 'required|digits:4',
            'name' => 'honeypot',
            'my_time' => 'required|honeytime:5',
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

        if (isset($data['month']) && isset($data['day']) && isset($data['year'])) {
            $data['birth'] = $data['year'].'-'.$data['month'].'-'.$data['day'];
        }

        $this->validator($data)->validate();
        $user = $this->create($data);
        event(new Registered($user));

        if ($user) {
//            $this->guard()->login($user);
            UserVerification::generate($user);
            UserVerification::sendQueue($user, 'Please Confirm Your Email');

            return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with('success', 'Confirm email sent!');
        }

        return false;
    }
}
