@extends('layouts.master')

@section('title', 'Ingredients')

@section('content')
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
            @foreach($ingredients as $ingredient)
                <li><a href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{!! $ingredient->title !!}</a></li>
            @endforeach
            </ul>
        </div>
    </div>

    @include('ingredients.footer-option')
@stop