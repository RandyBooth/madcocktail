@extends('layouts.master')

@section('title', 'Recipes')

@php $random_color = []; @endphp

@section('content')
    @if(!$recipes->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <h3>Latest Recipes</h3>

            <hr>

            <div class="row">
                @foreach($recipes as $recipe)
                    @php
                        if (!isset($random_color[$recipe->username])) {
                            $random_color[$recipe->username] = Helper::get_cache_random_color($recipe->username);
                        }
                    @endphp
                <div class="col-12 mb-5 col-lg-6 mb-lg-4">
                    @include('layouts.recipe-card', compact('random_color'))
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
                    <a href="{{ route('recipes.show', ['token' => $recipe->token, 'slug' => $recipe->slug]) }}">{{ $recipe->title }}</a>
                </li>
            @endforeach
            </ol>
        </div>
    </div>
    @endif
@stop