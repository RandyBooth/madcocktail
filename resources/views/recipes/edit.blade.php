@extends('recipes.template')

@section('title', 'Edit Recipe')

@section('form')
<form method="POST" action="{{ route('recipes.update', $recipe['token']) }}">
    {{ method_field('PUT') }}
@stop