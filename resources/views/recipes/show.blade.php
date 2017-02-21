@extends('layouts.master')

@php $title = (!empty($recipe->title)) ? $recipe->title . ' - ': ''; @endphp
@section('title', $title . 'Recipe')

@if(Helper::is_owner($recipe->user_id))
@section('script-bottom')
    <script src="{{ asset('js/image-upload-min.js') }}"></script>
@stop
@endif

@section('content')
    <div class="row">
        <div class="col-12 col-md-7 order-md-last">
            <div id="image" class="image">
                <img id="image-preview" class="image-preview" src="@if(!empty($recipe_image->image)){{route('imagecache', ['template' => 'show', 'filename' => $recipe_image->image])}}@endif">

                @if(Helper::is_owner($recipe->user_id))
                <div id="image-loading" class="image-loading hidden-xs-up">
                    <div class="image-loading-parent">
                        <div class="image-loading-child">
                            <i class="fa fa-refresh fa-spin fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div id="image-edit" class="image-edit">

                        <form action="{{ route('ajax_recipe_image_destroy') }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <div class="hidden-xs-up">
                                <input type="text" name="id" value="{{ $recipe->token }}">
                            </div>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <button type="button" id="image-edit-change" class="btn btn-gray" href="#"><i class="fa fa-camera" aria-hidden="true"></i> @if(!empty($recipe_image->image)){!! '<span>Update' !!}@else{!! '<span class="image-edit__add">Add' !!}@endif</span> Image</button>
                                {{--<button onclick="return confirm('Are you sure you want to delete?')" type="submit" class="btn btn-danger @if(empty($recipe_image->image)){{ 'hidden-xs-up' }}@endif"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
                            </div>
                        </form>

                    <form action="{{ route('ajax_recipe_image') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}

                        <div class="hidden-xs-up">
                            <input type="text" name="id" value="{{ $recipe->token }}">
                            <input id="image-edit-file" type="file" name="image" class="form-control">
                            <button class="btn btn-success" type="submit">Upload Image</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-md-5">
        @if (!empty($recipe->title))
            <h1>{{ $recipe->title_sup }}</h1>
        @endif

        @if (!empty($recipe->description))
            <p>{{ $recipe->description }}</p>
        @endif
        </div>
    </div>

    @if (!$ingredients->isEmpty())
    <div class="row">
        <div class="col-12">
            <h3>Ingredients</h3>

            <ul class="list-unstyled">
                @foreach($ingredients as $val)
                    <?php
                        $ingredient_title = $val->title_sup;
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
    <div class="row">
        <div class="col-12">
            <h3>Directions</h3>

            <ol>
                @foreach($recipe->directions as $val)
                    <li>{{ $val }}</li>
                @endforeach
            </ol>
        </div>
    </div>
    @endif

    @include('recipes.footer-option')
@stop

@section('sidebar-right')
    @if ($recipe_similar)
    <div class="row">
        <div class="col-12">
            <h3>Similar Recipes</h3>

            <ul class="list-unstyled">
                @foreach($recipe_similar as $val)
                    <li><a href="{{ route('recipes.show', ['id' => $val->slug]) }}">{{ $val->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@stop