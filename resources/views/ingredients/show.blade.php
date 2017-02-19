@extends('layouts.master')

@section('title', $ingredient->title. ' - Ingredient')

@section('content')
    <div class="row">
        <div class="col-12">
            {!! Helper::breadcrumbs($ingredient_breadcrumbs, 'ingredients.show', 'Ingredients') !!}
        </div>
    </div>

    @include('ingredients.subheader')

    <div class="row">
        <div class="col-12">
            <h1>{!! $ingredient->title_sup !!}</h1>

            @if (!$ingredients->isEmpty())
            <ul class="list-unstyled">
            @foreach($ingredients as $val)
                <li><a href="{{ route('ingredients.show', $parameters) }}/{{ $val->slug }}">{!! $val->title_sup !!}</a></li>
            @endforeach
            </ul>
            @endif
        </div>
    </div>

    @if(Helper::is_admin())
    <div class="row">
        <div class="col-12">
            <form action="{{ route('ingredients.destroy', $ingredient->token) }}" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group" aria-label="">
                    <a class="btn btn-primary" href="{{ route('ingredients.edit', $ingredient->token) }}"><i class="fa fa-pencil mr-1" aria-hidden="true"></i> Edit</a>
                    <button onclick="return confirm('Are you sure you want to delete?')" type="submit" class="btn btn-danger"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@stop

@section('sidebar-right')
    @if (!$recipes->isEmpty())
    <div class="row">
        <div class="col-12">
            <h3>Top recipes</h3>

            <ol>
            @foreach($recipes as $recipe)
                <li><a href="{{ route('recipes.show', $recipe->slug) }}">{!! $recipe->title_sup !!}</a></li>
            @endforeach
            </ol>
        </div>
    </div>
    @endif
@stop