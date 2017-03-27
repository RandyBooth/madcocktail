@extends('user-settings.default')

@section('title', 'Password - Settings')

@section('content-top')
    <h1>Password</h1>

    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <form method="POST" action="{{ route('user-settings.password.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}

                <div class="row">
                    <div class="col-12">
                        <div class="form-group{{ $errors->has('password_current') ? ' has-danger' : '' }} mb-5">
                            <label for="password-current" class="w-100 form-control-label">Current Password</label>

                            <input id="password-current" type="password" class="form-control" name="password_current" required autofocus>

                            @if ($errors->has('password_current'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('password_current') }}</strong>
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
                            <label for="password-confirm" class="w-100 form-control-label">Confirm Password</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop