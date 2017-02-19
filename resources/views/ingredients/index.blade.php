@extends('layouts.master')

@section('title', 'Ingredients')

@section('content')
    @include('ingredients.subheader')

    <ul class="list-unstyled">
    @foreach($ingredients as $ingredient)
        <li><a href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{!! $ingredient->title !!}</a></li>
    @endforeach
    </ul>
@stop