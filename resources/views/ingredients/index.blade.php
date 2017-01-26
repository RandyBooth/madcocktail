@extends('layouts.master')

@section('content')
    @include('ingredients.subheader')

    <ul>
    @foreach($ingredients as $ingredient)
        <li><a href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{!! $ingredient->title !!}</a></li>
    @endforeach
    </ul>
@stop