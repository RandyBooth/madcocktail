@extends('layouts.master')

@section('title', 'Register')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap.css') }}">
@stop

@section('script-bottom')
    <script src="{{ asset('js/vendor/select2.min.js') }}"></script>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <h1>Register</h1>

            <hr>

            <h4 class="mt-4 mb-3">Register with social media</h4>

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

            <h4 class="mt-4 mb-3">Filling out the form</h4>

            <form role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}

                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                            <label for="username" class="w-100 form-control-label">Username</label>

                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="w-100 form-control-label">E-Mail Address</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
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

                    <div class="col-12 col-sm-6">
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password-confirm" class="w-100 form-control-label">Confirm Password</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ ($errors->has('birth') || $errors->has('month') || $errors->has('day') || $errors->has('year')) ? ' has-danger' : '' }}">
                            <div class="row">
                                <label class="col-12 form-control-label">Date of Birth</label>

                                <div class="col-sm-5 mb-3">
                                    @php
                                        $old_birth_month = old('month');

                                        $count = 0;
                                        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

                                        echo '<select id="month" type="month" class="form-control select2-set" name="month" required>';
                                        echo '<option value="">Month</option>';
                                        for($count; $count < 12; $count++) {
                                            $selected = (($count+1) == $old_birth_month) ? ' selected' : '';
                                            echo '<option'.$selected.' value="'.str_pad(($count+1), 2, '0', STR_PAD_LEFT).'">'.$months[$count].'</option>';
                                        }
                                        echo '</select>';
                                    @endphp
                                    </select>
                                </div>

                                <div class="col-sm-3 mb-3">
                                    @php
                                        $old_birth_day = old('day');

                                        $count = 1;
                                        $days = 31;

                                        echo '<select id="day" type="day" class="form-control select2-set" name="day" required>';
                                        echo '<option value="">Day</option>';
                                        for($count; $count <= $days; $count++) {
                                            $day = str_pad(($count), 2, '0', STR_PAD_LEFT);
                                            $selected = ($day == $old_birth_day) ? ' selected' : '';
                                            echo '<option'.$selected.' value="'.$day.'">'.$day.'</option>';
                                        }
                                        echo '</select>';
                                    @endphp
                                    </select>
                                </div>

                                <div class="col-sm-4 mb-3">
                                    @php
                                        $old_birth_year = old('year');

                                        $count = 0;
                                        $count_max = 100;
                                        $year = \Carbon\Carbon::create(null, 01, 01, 0)->subYears(13)->year;

                                        echo '<select id="year" type="year" class="form-control select2-set" name="year" required>';
                                        echo '<option value="">Year</option>';
                                        for($count; $count <= $count_max; $count++) {
                                            $selected = ($year == $old_birth_year) ? ' selected' : '';
                                            echo '<option'.$selected.' value="'.$year.'">'.$year.'</option>';
                                            --$year;
                                        }
                                        echo '</select>';
                                    @endphp
                                    </select>
                                </div>

                                <div class="col-12">
                                    @if ($errors->has('birth'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('birth') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-12">
                                    @if ($errors->has('month'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('month') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-12">
                                    @if ($errors->has('day'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('day') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-12">
                                    @if ($errors->has('year'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('year') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Register
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
