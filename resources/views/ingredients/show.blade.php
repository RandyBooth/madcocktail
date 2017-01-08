@extends('layouts.master')

@section('content')

    <h1>{!! $ingredient->title !!}</h1>

    @if (!empty($ingredient_children))
    <ul>
    @foreach($ingredient_children as $child)
        <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $child->slug }}">{{ $child->title }}</a></li>
    @endforeach
    </ul>
    @endif

    @if (!empty($recipes))
    <h3>Top recipes:</h3>

    <ol>
    @foreach($recipes as $recipe)
        <li><a href="{{ route('recipes.show', $recipe->slug) }}">{{ $recipe->title }}</a></li>
    @endforeach
    </ol>
    @endif
@stop