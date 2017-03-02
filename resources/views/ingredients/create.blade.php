@extends('ingredients.template')

@section('title', 'Create New Ingredient')

@section('form')
    <form role="form" method="POST" action="{{ route('ingredients.store') }}">
@stop