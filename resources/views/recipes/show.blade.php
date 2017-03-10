@extends('layouts.master')

@php $title = (!empty($recipe->title)) ? $recipe->title . ' - ': ''; @endphp
@section('title', $title . 'Recipe')

@php
    $image = (!empty($recipe_image->image)) ? $recipe_image->image : 'mad-cocktail.gif';
    $social_title = $recipe->title. ' Recipe';
    $social_description = Helper::nl2empty($recipe->description);
    $social_image = route('imagecache', ['template' => 'share', 'filename' => $image]);
@endphp

@section('og-title', $social_title)
@section('og-description', $social_description)
@section('og-image', $social_image)

@if(Helper::is_owner($recipe->user_id))
@section('script-bottom')
    <script src="{{ Bust::url('/js/image-upload-min.js', true) }}"></script>
@stop
@endif

@section('content')
    <div class="row">
        <div class="col-12 mb-4 col-lg-7 order-lg-last col-xl-6">
            <div id="image" class="image">
                @php
                    $class_blur = '';

                    if (!empty($recipe_image->image)) {
                        $image = $recipe_image->image;
                        $class_blur = ' image-blur';
                    } else {
                        $image = 'blank.gif';
                    }
                @endphp
                <img id="image-preview" class="image-preview card-img-top img-fluid{{$class_blur}}" data-original="{{ route('imagecache', ['template' => 'single', 'filename' => $image]) }}" src="{{ route('imagecache', ['template' => 'single-tiny', 'filename' => $image]) }}" alt="">

                @if(Helper::is_owner($recipe->user_id))
                <div id="image-loading" class="image-loading hidden-xs-up">
                    <div class="image-loading-parent">
                        <div class="image-loading-child">
                            <i class="fa fa-refresh fa-spin fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div id="image-edit" class="image-edit">

                    <form action="{{ route('ajax_recipe_image') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}

                        <div class="hidden-xs-up">
                            <input type="text" name="id" value="{{ $recipe->token }}">
                            <input id="image-edit-file" type="file" name="image" class="form-control">
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="">
                            <button type="button" id="image-edit-change" class="btn btn-gray" href="#"><i class="fa fa-camera" aria-hidden="true"></i> @if(!empty($recipe_image->image)){!! '<span>Update' !!}@else{!! '<span class="image-edit__add">Add' !!}@endif</span> Image</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="col-12 mb-4 col-lg-5 col-xl-6">
            <div class="row">
                @if (!empty($recipe->title))
                    <div class="col-12">
                        <h1>{{ $recipe->title_sup }}</h1>
                    </div>
                @endif

                @if (!empty($recipe->description))
                        <div class="col-12">
                            {!! Helper::nl2p($recipe->description, false) !!}
                        </div>
                @endif

                <div class="col-12 mt-2">
                    @php $author = (!empty($recipe_author->display_name)) ? $recipe_author->display_name : $recipe_author->username; @endphp
                    Recipe by <a class="link" href="{{ route('user-profile.show', ['username' => $recipe_author->username]) }}">{{ $author }}</a>
                </div>
            </div>
        </div>
    </div>

    @if (!$ingredients->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <h3>Ingredients</h3>

            <hr>

            <ul class="list-unstyled">
                @foreach($ingredients as $val)
                    <?php
                        $ingredient_title = $val->title;
                        $measure_title = strtolower($val->pivot->measure_title);
                        $measure_amount = $val->pivot->measure_amount;
                        $measure_amount_fraction = $val->pivot->measure_amount_fraction;
                        $string = '';

                        if (!empty($ingredient_title)) {
                            if (!empty($measure_amount_fraction)) {
                                $string .= $measure_amount_fraction.' ';

                                if (!empty($measure_title)) {
                                    $string .= ($measure_amount > 1 ) ? str_plural($measure_title) : str_singular($measure_title);
                                    $string .= ' ';
                                } else {
                                    if ($measure_amount > 1) {
                                        $ingredient_title = str_plural($ingredient_title);
                                    }
                                }
                            } else {
                                if (!empty($measure_title)) {
                                    $string .= str_singular($measure_title) . ' of';
                                    $string .= ' ';
                                }
                            }

                            if ($val->is_active) {
                                if (isset($ingredient_slug[$val->id])) {
                                    $ingredient_title = '<a class="link" href="'.route('ingredients.show', $ingredient_slug[$val->id]).'">'.$ingredient_title.'</a>';
                                }
                            }

                            $string .= $ingredient_title.' ';
                        }
                    ?>
                    <li>{!! $string !!}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (!empty($recipe->directions))
    <div class="row mb-2">
        <div class="col-12">
            <h3>Directions</h3>

            <hr>

            <ol>
                @foreach($recipe->directions as $val)
                    <li>{{ $val }}</li>
                @endforeach
            </ol>
        </div>
    </div>
    @endif

    @if (!empty($recipe->glass->title))
    <div class="row mb-5">
        <div class="col-12">
            Glass: {{ $recipe->glass->title }}
        </div>
    </div>
    @endif

    <div class="row mt-4 mb-md-4">
        <div class="col-12">
            <h3 class="hidden-sm-down">Share</h3>

            <hr class="hidden-sm-down">

            {!! Helper::share($social_title, $social_description, $social_image) !!}
        </div>
    </div>

    @include('recipes.footer-option')
@stop

@section('sidebar-right')
    @if (!$recipe_similar->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <h3>Similar Recipes</h3>

            <ul class="list-unstyled">
                @foreach($recipe_similar as $val)
                    <li><a class="link" href="{{ route('recipes.show', ['id' => $val->slug]) }}">{{ $val->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@stop