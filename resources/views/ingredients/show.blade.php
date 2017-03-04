@extends('layouts.master')

@php $title = (isset($ingredient->title)) ? $ingredient->title . ' - Ingredient' : 'Ingredients'; @endphp
@php $title_sup = (isset($ingredient->title_sup)) ? $ingredient->title_sup : 'Ingredients'; @endphp

@section('title', $title)

@section('breadcrumb')
    @if (!empty($ingredient_breadcrumbs))
        {!! Helper::breadcrumbs($ingredient_breadcrumbs, 'ingredients.show', 'Ingredients') !!}
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{!! $title_sup !!}</h1>

            @if (!$ingredients->isEmpty())
            <div class="row">
                {{--<div class="col-12">--}}
                    @php
                        $total = count($ingredients);
                        $mid = ceil($total/2);
                    @endphp
                    @foreach($ingredients as $val)
                        @if ($loop->iteration == 1 || $loop->iteration == ($mid+1))
                            @php $position = ($loop->iteration == 1) ? 'first' : 'last' @endphp
                            <div class="col-12 col-lg-6"><div class="list-group list-group-{{$position}}">
                        @endif
                        <a class="list-group-item list-group-item-action" href="{{ route('ingredients.show', $parameters) }}/{{ $val->slug }}">{!! $val->title !!}</a>
                        @if ($loop->iteration == $total || $loop->iteration == $mid)</div></div>@endif
                    @endforeach
                {{--</div>--}}
            </div>
            @endif
        </div>
    </div>

    @include('ingredients.footer-option')
@stop

@section('sidebar-right')
    @if (!$recipes->isEmpty())
    <div class="row">
        <div class="col-12">
            <h3 class="mb-0">Popular recipes</h3>
            {!! (isset($ingredient->title_sup)) ? '<h5>'.strtolower($ingredient->title_sup).'</h5>' : '' !!}

            <ol>
            @foreach($recipes as $recipe)
                <li><a href="{{ route('recipes.show', $recipe->slug) }}">{!! $recipe->title_sup !!}</a></li>
            @endforeach
            </ol>
        </div>
    </div>
    @endif
@stop