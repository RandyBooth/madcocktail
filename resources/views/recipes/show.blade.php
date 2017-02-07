@extends('layouts.master')

@section('title', $recipe->title. ' - Recipe')

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
        <img id="preview" class="preview" src="@if(isset($recipe_image->image)){{route('imagecache', ['template' => 'large', 'filename' => $recipe_image->image])}}@endif">

        @if(!isset($recipe_image->image) && Helper::is_owner($recipe->user_id))
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

    @if (!empty($ingredients))
    <div><strong>Ingredients:</strong>
        <ul>
            @foreach($ingredients as $ingredient)
                <?php
                    $ingredient_title = $ingredient->title_sup;
                    $measure_title = strtolower($ingredient->pivot->measure_title);
                    $measure_amount = $ingredient->pivot->measure_amount;
                    $measure_amount_fraction = $ingredient->pivot->measure_amount_fraction;
                    $string = '';

                    if (!empty($ingredient_title)) {
                        if (!empty($measure_amount_fraction)) {
                            $string .= $measure_amount_fraction.' ';

                            if (!empty($measure_title)) {
                                $string .= ($measure_amount > 1 ) ? str_plural($measure_title) : str_singular($measure_title);
                                $string .= ' ';
                            }
                        }

                        if ($ingredient->is_active) {
                            if (isset($ingredient_slug[$ingredient->id])) {
                                $ingredient_title = '<a href="'.route('ingredients.show', $ingredient_slug[$ingredient->id]).'">'.$ingredient_title.'</a>';
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
            @foreach($recipe->directions as $direction)
                <li>{{ $direction }}</li>
            @endforeach
        </ol>
    </div>
    @endif
    <pre>
        <form action="{{ route('recipes.destroy', $recipe->slug) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="submit" value="Delete">
        </form>
    </pre>
@stop