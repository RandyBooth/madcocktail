@extends('layouts.master')

@section('title', 'Recipes')

@section('script-bottom')
@stop

@section('content')
    @if(!empty($recipes_latest))
    <div class="row">
        <div class="col-12">
            <h3>Latest Recipes</h3>

            <div class="row">
                @foreach($recipes_latest as $recipe)
                <div class="col-12 mb-3 col-md-6">
                    <div class="card">
                        <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
                            <img class="card-img-top img-fluid" data-original="@if(!empty($recipe->image)){{route('imagecache', ['template' => 'lists', 'filename' => $recipe->image])}}@endif" src="@if(!empty($recipe->image)){{route('imagecache', ['template' => 'tiny', 'filename' => $recipe->image])}}@endif" alt="">
                        </a>
                        <div class="card-block">
                            <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
                                <h4 class="card-title">{{ $recipe->title }}</h4>
                            </a>
                            <p class="card-text">{{ $recipe->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop

@section('sidebar-right')
    @if(!empty($recipes_top))
    <div class="row">
        <div class="col-12">
            <h3>Popular Recipes</h3>

            {{--<div class="row">
            @foreach($recipes_top as $recipe)
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">
                            <img class="card-img-top img-fluid" data-original="@if(!empty($recipe->image)){{route('imagecache', ['template' => 'lists', 'filename' => $recipe->image])}}@endif" src="@if(!empty($recipe->image)){{route('imagecache', ['template' => 'tiny', 'filename' => $recipe->image])}}@endif" alt="">
                        </div>
                        <div class="col-9">
                            <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">{{ $recipe->title }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>--}}

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