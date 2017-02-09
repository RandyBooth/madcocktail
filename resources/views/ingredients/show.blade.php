@extends('layouts.master')

@section('title', $ingredient->title. ' - Ingredient')

@section('content')
    {!! Helper::breadcrumbs($ingredient_breadcrumbs, 'ingredients.show', 'Ingredients') !!}

    @include('ingredients.subheader')

    <h1>{!! $ingredient->title_sup !!}</h1>

    @if (!$ingredients->isEmpty())
    <ul>
    @foreach($ingredients as $val)
        <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $val->slug }}">{!! $val->title_sup !!}</a></li>
    @endforeach
    </ul>
    @endif

    @if (!$recipes->isEmpty())
    <h3>Top recipes:</h3>

    <ol>
    @foreach($recipes as $recipe)
        <li><a href="{{ route('recipes.show', $recipe->slug) }}">{!! $recipe->title_sup !!}</a></li>
    @endforeach
    </ol>
    @endif

    <div>
        <form action="{{ route('ingredients.destroy', $ingredient->token) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <input type="submit" value="Delete">
        </form>
    </div>
@stop