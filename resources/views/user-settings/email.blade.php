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
            <form method="POST" action="{{ route('user-settings.email.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('name', 'my_time') !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">E-Mail Address</label>

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $email) }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@stop