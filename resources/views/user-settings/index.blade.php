@extends('user-settings.default')

@section('title', 'Settings')

@section('content-top')
    <h1>Settings</h1>

    <hr>
@stop

@section('style')
    <link rel="stylesheet" href="{{ Bust::url('/css/vendor/select2-bootstrap.css', true) }}">
@stop

@section('script-bottom')
    <script src="{{ Bust::url('/js/vendor/select2.min.js', true) }}"></script>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <form method="POST" action="{{ route('user-settings.index.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}

                <div class="row">

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                            <label for="username" class="w-100 form-control-label">Username</label>

                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" @if(!empty($user->username)){{ 'readonly' }}@else{{ 'autofocus' }}@endif required>

                            @if ($errors->has('username'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @php
                        $year = '';
                        $month = '';
                        $day = '';

                        if (!empty($user->birth)) {
                            $birth = $user->birth;

                            if (Helper::check_my_date($birth)) {
                                $date = explode('-', $birth);

                                $year = $date[0];
                                $month = $date[1];
                                $day = $date[2];
                            }
                        }
                    @endphp

                    <div class="col-12">
                        <div class="form-group{{ ($errors->has('birth') || $errors->has('month') || $errors->has('day') || $errors->has('year')) ? ' has-danger' : '' }}">
                            <div class="row">
                                <label class="col-12 form-control-label">Date of Birth</label>

                                <div class="col-sm-5 mb-3">
                                    @php
                                        $old_birth_month = old('month', $month);

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
                                        $old_birth_day = old('day', $day);

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
                                        $old_birth_year = old('year', $year);

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
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop