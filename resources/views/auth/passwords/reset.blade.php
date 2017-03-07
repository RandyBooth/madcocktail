@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <h1>Reset Password</h1>

            <hr>

            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <input type="hidden" name="email" value="{{ $email }}">

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

                            <input id="password" type="password" class="form-control" name="password" required autofocus>

                            @if ($errors->has('password'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                            <label for="password-confirm" class="w-100 form-control-label">Confirm Password</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                            @if ($errors->has('password_confirmation'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Reset Password
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
