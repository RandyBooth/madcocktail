@extends('ingredients.template')

@section('title', 'Create New Ingredient')

@section('form')
    <form method="POST" action="{{ route('ingredients.store') }}">
@stop