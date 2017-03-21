@extends('layouts.master')

@section('title', 'Home')

@php $random_color = []; @endphp

@section('content')
    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach($recipes as $recipe)
                    @php
                        if (!isset($random_color[$recipe->username])) {
                            $random_color[$recipe->username] = Helper::get_cache_random_color($recipe->username);
                        }
                    @endphp
                <div class="col-12 mb-5 col-md-6 mb-md-4 col-lg-4">
                    @include('layouts.recipe-card', compact('random_color'))
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop