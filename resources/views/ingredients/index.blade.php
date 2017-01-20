@extends('layouts.master')

@section('content')
    <ul>
    @foreach($ingredients as $ingredient)
        <li><a href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{!! $ingredient->title !!}</a></li>
    @endforeach
    </ul>
@stop