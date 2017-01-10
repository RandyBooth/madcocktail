@extends('layouts.master')

@section('content')
    <form method="POST" action="{{ route('ingredients.store') }}">
        {{ csrf_field() }}
    </form>
@stop