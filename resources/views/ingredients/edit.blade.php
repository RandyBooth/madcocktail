@extends('ingredients.template')

@section('title', $ingredient['title'] . ' - Edit Ingredient')

@section('form')
<form role="form" method="POST" action="{{ route('ingredients.update', $ingredient['token']) }}">
    {{ method_field('PUT') }}
@stop