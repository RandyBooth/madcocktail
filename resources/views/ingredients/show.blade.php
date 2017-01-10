@extends('layouts.master')

@section('content')

    @include('ingredients.subheader')

    <h1>{!! $ingredient->title !!}</h1>

    @if (!empty($ingredients))
    <ul>
    @foreach($ingredients as $ingredient)
        <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $ingredient->slug }}">{{ $ingredient->title }}</a></li>
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