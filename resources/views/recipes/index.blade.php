@extends('layouts.master')

@section('title', 'Recipes')

@section('content')
    @if(!$recipes->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <h3>Latest Recipes</h3>

            <hr>

            <div class="row">
                @foreach($recipes as $recipe)
                <div class="col-12 mb-5 col-lg-6 mb-lg-4">
                    @include('layouts.recipe-card')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @include('recipes.footer-option')
@stop

@section('sidebar-right')
    @if(!$recipes_top->isEmpty())
    <div class="row">
        <div class="col-12">
            <h3>Popular Recipes</h3>

            <ol>
            @foreach($recipes_top as $recipe)
                <li>
                    <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">{{ $recipe->title }}</a>
                </li>
            @endforeach
            </ol>
        </div>
    </div>
    @endif
@stop