@extends('layouts.master')

@php $author = (!empty($user->display_name)) ? $user->display_name : $user->username; @endphp
@section('title', $author. '\'s Recipes')

@section('script-bottom')
@stop

@section('content')

    {{--<div class="row">
        <div class="col-12">
            <h1>Profile: {{ $author }}</h1>

            <hr>
        </div>
    </div>--}}

    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <h3>{{ $author }}'s Recipes</h3>

            <hr>

            <div class="row">
                @foreach($recipes as $recipe)
                <div class="col-12 mb-3 col-md-4">
                    @include('layouts.recipe-card')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @include('recipes.footer-option')
@stop