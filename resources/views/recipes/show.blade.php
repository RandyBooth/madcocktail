@extends('layouts.master')

@section('title', $recipe->title. ' | Recipes')

@section('content')
    @include('recipes.subheader')

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