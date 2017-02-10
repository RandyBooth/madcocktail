@extends('layouts.master')

@php $title = (!empty($recipe->title)) ? $recipe->title . ' - ': ''; @endphp
@section('title', $title . 'Recipe')

@if(Auth::id())
@if(Helper::is_owner($recipe->user_id))
@section('script-bottom')
    <script src="{{ asset('js/image-upload.js') }}"></script>
@stop
@endif
@endif

@section('content')
    @include('recipes.subheader')

    <div id="image" class="image">
        <img id="preview" class="preview" src="@if(!empty($recipe_image->image)){{route('imagecache', ['template' => 'large', 'filename' => $recipe_image->image])}}@endif">

        @if(empty($recipe_image->image) && Helper::is_owner($recipe->user_id))
        <div class="image-empty">
            <a id="add-image" href="#">Add Image</a>
            <form id="form-image" action="{{ route('ajax_recipe_image') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}

                <div style="display: none;">
                    <input type="text" name="id" value="{{ $recipe->token }}">
                    <input id="upload-image" type="file" name="upload-image" class="form-control">
                    <button class="btn btn-success upload-image" type="submit">Upload Image</button>
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
                                $string .= '1 '.str_singular($measure_title);
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
    <div>
        <form action="{{ route('recipes.destroy', $recipe->token) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="submit" value="Delete">
        </form>
    </div>
    @endif
@stop

@section('sidebar')
    Sidebar
@stop