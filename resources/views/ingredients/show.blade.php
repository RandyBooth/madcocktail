@extends('layouts.master')

@section('content')

    <h1>{!! $ingredient->title !!}</h1>

    <ul>
    @foreach($ingredient_children as $child)
        <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $child->slug }}">{{ $child->title }}</a></li>
    @endforeach
    </ul>

    <h3>Top recipes of this ingredient:</h3>
    <?php $count = 1; ?>

    <ol>
    @foreach($recipes as $recipe)
        <li><a href="{{ route('recipes.show', $recipe->slug) }}">{{ $recipe->title }}</a></li>
    @endforeach
    </ol>
@stop