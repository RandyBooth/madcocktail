@extends('user-settings.default')

@section('title', 'E-mail address - Settings')

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <form method="POST" action="{{ route('user-settings.email.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}


                <div class="row">
                    <div class="col-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="w-100 form-control-label">E-Mail Address</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $email) }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
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