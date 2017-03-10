@extends('layouts.master')

@section('title', 'Pending Lists - Ingredients')

@section('content')
    @if (!$ingredients->isEmpty())
        <div class="row">
            <div class="col-12">
                {{--<div class="list-group">
                    @foreach($ingredients as $ingredient)
                        @if(!empty($ingredients_slug[$ingredient->id]))
                            <a class="list-group-item list-group-item-action" href="{{ route('ingredients.edit', $ingredient->token) }}">{!! $ingredient->title !!} [{{ $ingredients_slug[$ingredient->id] }}]</a>
                        @endif
                    @endforeach
                </div>--}}

                <ul>
                    @foreach($ingredients as $ingredient)
                        @if(!empty($ingredients_slug[$ingredient->id]))
                            <li><a href="{{ route('ingredients.show', $ingredients_slug[$ingredient->id]) }}" target="_blank">{!! $ingredient->title !!}</a> [{{ $ingredients_slug[$ingredient->id] }}]</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@stop