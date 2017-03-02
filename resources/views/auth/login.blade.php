@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <h1>Login</h1>

            <hr>

            <h4 class="mt-4 mb-3">Login with social media</h4>
            
            <div class="social-media-auth row mb-4">
                <div class="col-4">
                    <a class="color-link-facebook" href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                </div>
                
                <div class="col-4">
                    <a class="color-link-twitter" href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </div>
                
                <div class="col-4">
                    <a class="color-link-googleplus" href="{{ route('oauth.redirect', ['provider' => 'google']) }}"><i class="fa fa-google" aria-hidden="true"></i></a>
                </div>
            </div>

            <hr>

            <h4 class="mt-4 mb-3">Login with e-mail</h4>

            <form role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="w-100 form-control-label">E-Mail Address</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password" class="w-100 form-control-label">Password</label>

                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>

                            <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
