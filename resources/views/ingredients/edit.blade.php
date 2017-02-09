@extends('ingredients.template')

@section('title', 'Edit Ingredient')

@section('form')
<form method="POST" action="{{ route('ingredients.update', $ingredient['token']) }}">
    {{ method_field('PUT') }}
@stop