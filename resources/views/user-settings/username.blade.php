@extends('user-settings.default')

@section('title', 'Username - Settings')

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <form method="POST" action="{{ route('user-settings.username.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('name', 'my_time') !!}

                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label for="username" class="control-label">Username</label>

                    <input id="username" type="username" class="form-control" name="username" value="{{ old('username', $username) }}" required autofocus>

                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
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