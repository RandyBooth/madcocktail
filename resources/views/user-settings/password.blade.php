@extends('user-settings.default')

@section('title', 'E-mail - Settings')

@section('style')
    {{--<link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap.css') }}">--}}
@stop

@section('script-bottom')
    {{--<script src="{{ asset('js/vendor/select2.min.js') }}"></script>--}}
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <form method="POST" action="{{ route('user-settings.password.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('name', 'my_time') !!}

                <div class="form-group{{ $errors->has('password-current') ? ' has-error' : '' }} mb-5">
                    <label for="password-current" class="control-label">Current Password</label>

                    <input id="password-current" type="password" class="form-control" name="password-current" >

                    @if ($errors->has('password-current'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password-current') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Password</label>

                    <input id="password" type="password" class="form-control" name="password" >

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="control-label">Confirm Password</label>

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@stop