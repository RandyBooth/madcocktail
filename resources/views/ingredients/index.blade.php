@extends('layouts.master')

@section('title', 'Ingredients')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row text-center">
                @foreach($ingredients as $ingredient)
                    <div class="col-12 col-sm-4 mb-4">
                        <div style="font-size: 20px;" class="card">
                            <a class="card-block" href="{{ route('ingredients.show', ['id' => $ingredient->slug]) }}">{!! $ingredient->title !!}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('ingredients.footer-option')
@stop

@section('sidebar-right')

@stop