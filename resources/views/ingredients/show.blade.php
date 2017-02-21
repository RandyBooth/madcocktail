@extends('layouts.master')

@section('title', $ingredient->title. ' - Ingredient')

@section('breadcrumb')
    {!! Helper::breadcrumbs($ingredient_breadcrumbs, 'ingredients.show', 'Ingredients') !!}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{!! $ingredient->title_sup !!}</h1>

            @if (!$ingredients->isEmpty())
            <ul class="list-unstyled">
            @foreach($ingredients as $val)
                <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $val->slug }}">{!! $val->title_sup !!}</a></li>
            @endforeach
            </ul>
            @endif
        </div>
    </div>

    @include('ingredients.footer-option')
@stop

@section('sidebar-right')
    @if (!$recipes->isEmpty())
    <div class="row">
        <div class="col-12">
            <h3>Top recipes</h3>

            <ol>
            @foreach($recipes as $recipe)
                <li><a href="{{ route('recipes.show', $recipe->slug) }}">{!! $recipe->title_sup !!}</a></li>
            @endforeach
            </ol>
        </div>
    </div>
    @endif
@stop