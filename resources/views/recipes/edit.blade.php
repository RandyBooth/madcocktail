@extends('recipes.template')

@section('title', $recipe['title'] . ' - Edit Recipe')

@section('form')
<form role="form" method="POST" action="{{ route('recipes.update', $recipe['token']) }}">
    {{ method_field('PUT') }}
@stop