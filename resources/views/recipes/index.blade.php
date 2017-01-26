@extends('layouts.master')

@section('title', 'Recipes')

@section('content')
    {{--@if(!empty($ingredients))
        <ul>
        @foreach($ingredients as $ingredient)
            <li>
                <ul>
                    <li><a href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{{ $ingredient->title }}</a></li>
                    <li>Description: {{ $ingredient->description }}</li>
                </ul>
            </li>
        @endforeach
        </ul>
    @endif--}}

    @if(!empty($recipes_latest))
        <ul>
        @foreach($recipes_latest as $recipe)
            <li>
                <ul>
                    <li><a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">{{ $recipe->title }}</a></li>
                    <li>Description: {{ $recipe->description }}</li>
                </ul>
            </li>
        @endforeach
        </ul>
    @endif

    @if(!empty($recipes_top))
        <ul>
        @foreach($recipes_top as $recipe)
            <li>
                <ul>
                    <li><a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">{{ $recipe->title }}</a></li>
                    <li>Description: {{ $recipe->description }}</li>
                </ul>
            </li>
        @endforeach
        </ul>
    @endif
@stop