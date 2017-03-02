@extends('layouts.master')

@section('title', 'Home')

@section('content')
    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach($recipes as $recipe)
                <div class="col-12 mb-3 col-md-4">
                    @include('layouts.recipe-card')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop