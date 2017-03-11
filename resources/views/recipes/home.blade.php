@extends('layouts.master')

@section('title', 'Home')

@section('content')
    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach($recipes as $recipe)
                <div class="col-12 mb-5 col-md-6 mb-md-4 col-lg-4">
                    @include('layouts.recipe-card')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop