@extends('recipes.template')

@section('title', 'Create New Recipe')

@section('form')
    <form method="POST" action="{{ route('recipes.store') }}">
@stop