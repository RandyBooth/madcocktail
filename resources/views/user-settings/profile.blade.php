@extends('user-settings.default')

@section('title', 'Profile Settings - Settings')

@section('script-bottom')
    <script src="{{ Bust::url('/js/image-upload-min.js', true) }}"></script>
@stop

@section('content-top')
    <h1>Profile Settings</h1>

    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <label class="w-100 form-control-label">Profile Image</label>

            <div style="max-width: 200px;" id="image" class="image mb-4">
                @php
                    $image = (!empty($user->image)) ? $user->image : 'blank.gif';
                @endphp
                <img id="image-preview" class="image-preview img-fluid" src="{{ route('imagecache', ['template' => 'user-normal', 'filename' => $image]) }}" alt="">

                <div id="image-loading" class="image-loading hidden-xs-up">
                    <div class="image-loading-parent">
                        <div class="image-loading-child">
                            <i class="fa fa-refresh fa-spin fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div id="image-edit" class="image-edit">
                    <form action="{{ route('ajax_profile_image') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}

                        <div class="hidden-xs-up">
                            <input id="image-edit-file" type="file" name="image" class="form-control">
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="">
                            <button type="submit" id="image-edit-change" class="btn btn-gray"><i class="fa fa-camera" aria-hidden="true"></i> @if(!empty($user->image)){!! '<span>Update' !!}@else{!! '<span class="image-edit__add">Add' !!}@endif</span> Image</button>
                        </div>
                    </form>
                </div>
            </div>

            <form method="POST" action="{{ route('user-settings.profile.update') }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}

                <div class="row">
                    <div class="col-12">
                        <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                            <label for="display-name" class="w-100 form-control-label">Display Name</label>

                            <input id="display-name" type="text" class="form-control" name="display_name" value="{{ old('display_name', $user->display_name) }}" autofocus>

                            @if ($errors->has('display_name'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('display_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('about') ? ' has-danger' : '' }}">
                            <label for="about" class="w-100 form-control-label">About Me</label>

                            <textarea name="about" id="about" class="form-control" rows="4">{{ old('about', $user_settings->about) }}</textarea>

                            @if ($errors->has('about'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('about') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('link') ? ' has-danger' : '' }}">
                            <label for="link" class="w-100 form-control-label">Website</label>

                            <input id="link" type="text" class="form-control" name="link" value="{{ old('link', $user_settings->link) }}" placeholder="{{ route('home') }}">

                            @if ($errors->has('link'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('link') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('facebook_link') ? ' has-danger' : '' }}">
                            <label for="facebook-link" class="w-100 form-control-label">Facebook Link</label>

                            <input id="facebook-link" type="text" class="form-control" name="facebook_link" value="{{ old('facebook_link', $user_settings->facebook_link) }}">

                            @if ($errors->has('facebook_link'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('facebook_link') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('google_plus_link') ? ' has-danger' : '' }}">
                            <label for="google-plus-link" class="w-100 form-control-label">Google+ Link</label>

                            <input id="google-plus-link" type="text" class="form-control" name="google_plus_link" value="{{ old('google_plus_link', $user_settings->google_plus_link) }}">

                            @if ($errors->has('google_plus_link'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('google_plus_link') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('pinterest_link') ? ' has-danger' : '' }}">
                            <label for="pinterest-link" class="w-100 form-control-label">Pinterest Link</label>

                            <input id="pinterest-link" type="text" class="form-control" name="pinterest_link" value="{{ old('pinterest_link', $user_settings->pinterest_link) }}">

                            @if ($errors->has('pinterest_link'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('pinterest_link') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group{{ $errors->has('twitter_link') ? ' has-danger' : '' }}">
                            <label for="twitter-link" class="w-100 form-control-label">Twitter Link</label>

                            <input id="twitter-link" type="text" class="form-control" name="twitter_link" value="{{ old('twitter_link', $user_settings->twitter_link) }}">

                            @if ($errors->has('twitter_link'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('twitter_link') }}</strong>
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