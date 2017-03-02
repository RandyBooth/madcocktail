@extends('recipes.template')

@section('title', 'Create New Recipe')

@section('form')
    <form role="form" method="POST" action="{{ route('recipes.store') }}">
@stop