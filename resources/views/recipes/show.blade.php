@extends('layouts.master')

@php $title = (!empty($recipe->title)) ? $recipe->title . ' - ': ''; @endphp
@section('title', $title . 'Recipe')

@if(Helper::is_owner($recipe->user_id))
@section('script-bottom')
    <script src="{{ asset('js/image-upload-min.js') }}"></script>
@stop
@endif

@section('content')
    {{--@include('recipes.subheader')--}}

    <div id="image" class="image">
        <img id="image-preview" class="image-preview" src="@if(!empty($recipe_image->image)){{route('imagecache', ['template' => 'large', 'filename' => $recipe_image->image])}}@endif">

        @if(empty($recipe_image->image) && Helper::is_owner($recipe->user_id))
        <div id="image-edit" class="image-edit">
            <a id="image-edit-change" href="#">Add Image</a>

            <form action="{{ route('ajax_recipe_image') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}

                <div class="hidden-xl-down">
                    <input type="text" name="id" value="{{ $recipe->token }}">
                    <input id="image-edit-file" type="file" name="image" class="form-control">
                    <button class="btn btn-success" type="submit">Upload Image</button>
                </div>
            </form>
        </div>
        @endif
    </div>


    @if (!empty($recipe->title))
    <div><strong>Title:</strong> {{ $recipe->title_sup }}</div>
    @endif

    @if (!empty($recipe->description))
    <div><strong>Description:</strong> {{ $recipe->description }}</div>
    @endif

    @if (!$ingredients->isEmpty())
    <div><strong>Ingredients:</strong>
        <ul>
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
                                $ingredient_title = '<a href="'.route('ingredients.show', $ingredient_slug[$val->id]).'">'.$ingredient_title.'</a>';
                            }
                        }

                        $string .= $ingredient_title.' ';
                    }
                ?>
                <li>{!! $string !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (!empty($recipe->directions))
    <div><strong>Directions:</strong>
        <ol>
            @foreach($recipe->directions as $val)
                <li>{{ $val }}</li>
            @endforeach
        </ol>
    </div>
    @endif

    @if(Helper::is_admin())
    <div class="row">
        <div class="col-auto mr-1">
            <a class="btn btn-primary" href="{{ route('recipes.edit', $recipe->token) }}">Edit</a>
        </div>
        <div class="col-auto">
            <form onsubmit="return confirm('Are you sure you want to delete?')" action="{{ route('recipes.destroy', $recipe->token) }}" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
    @endif
@stop

@section('sidebar')
    @if ($recipe_similar)
        <div><strong>Similar Recipes:</strong>
            <ol>
                @foreach($recipe_similar as $val)
                    <li><a href="{{ route('recipes.show', ['id' => $val->slug]) }}">{{ $val->title }}</a></li>
                @endforeach
            </ol>
        </div>
    @endif
@stop