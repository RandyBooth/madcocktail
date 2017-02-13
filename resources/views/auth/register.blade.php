@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" >

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birth') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Date of Birth</label>

                            <div class="col-md-6">
                                @if ($errors->has('birth'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birth') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('month') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                @php
                                    $old_birth_month = old('month');

                                    $count = 0;
                                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

                                    echo '<select id="month" type="month" class="form-control" name="month" >';
                                    echo '<option value="">Month</option>';
                                    for($count; $count < 12; $count++) {
                                        $selected = (($count+1) == $old_birth_month) ? ' selected' : '';
                                        echo '<option'.$selected.' value="'.str_pad(($count+1), 2, '0', STR_PAD_LEFT).'">'.$months[$count].'</option>';
                                    }
                                    echo '</select>';
                                @endphp
                                </select>

                                <div class="col-md-6">
                                    @if ($errors->has('month'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('month') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birth') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                @php
                                    $old_birth_day = old('day');

                                    $count = 1;
                                    $days = 31;

                                    echo '<select id="day" type="day" class="form-control" name="day" >';
                                    echo '<option value="">Day</option>';
                                    for($count; $count <= $days; $count++) {
                                        $day = str_pad(($count), 2, '0', STR_PAD_LEFT);
                                        $selected = ($day == $old_birth_day) ? ' selected' : '';
                                        echo '<option'.$selected.' value="'.$day.'">'.$day.'</option>';
                                    }
                                    echo '</select>';
                                @endphp
                                </select>

                                <div class="col-md-6">
                                    @if ($errors->has('day'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('day') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                @php
                                    $old_birth_year = old('year');

                                    $count = 0;
                                    $count_max = 100;
                                    $year = \Carbon\Carbon::create(null, 01, 01, 0)->subYears(13)->year;

                                    echo '<select id="year" type="year" class="form-control" name="year" >';
                                    echo '<option value="">Year</option>';
                                    for($count; $count <= $count_max; $count++) {
                                        $selected = ($year == $old_birth_year) ? ' selected' : '';
                                        echo '<option'.$selected.' value="'.$year.'">'.$year.'</option>';
                                        --$year;
                                    }
                                    echo '</select>';
                                @endphp
                                </select>

                                <div class="col-md-6">
                                    @if ($errors->has('year'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('year') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
