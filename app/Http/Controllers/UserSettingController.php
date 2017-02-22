<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class UserSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function usernameEdit()
    {
        $user = Auth::user();
        $username = $user->username;

        return view('user-settings.username', compact('username'));
    }

    public function usernameUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'username' => [
                'required', 'min:3', 'max:255', 'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            $user->username = $data['username'];

            if ($user->save()) {
                return redirect()->route('user-settings.username.edit')->with('success', 'Username has been updated successfully.');
            }
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('danger', 'Username update fail.');
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
            'name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            $old_email = $user->email;
            $user->email = $data['email'];

            if ($user->save()) {
                if (strtolower($old_email) != strtolower($data['email'])) {
                    \Jrean\UserVerification\Facades\UserVerification::generate($user);
                    \Jrean\UserVerification\Facades\UserVerification::sendQueue($user, 'Please Confirm Your Email');
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
            'password-current' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'name' => 'honeypot',
            'my_time' => 'required|honeytime:1',
        ]);

        if ($validator->passes()) {
            if (\Hash::check($data['password-current'], $user->password)) {
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /*
     * Edit email
     */
    public function editEmail($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
