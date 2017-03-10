@extends('layouts.master')

@section('title', 'Lists - Recipes')
@section('content')
    @if (!$recipes->isEmpty())
        <div class="row">
            <div class="col-12">
                @foreach($recipes as $recipe)
                    @php $matched = []; @endphp

                    <hr>
                    <div class="row">
                        <div class="col-2">
                            <a href="{{ route('recipes.show', $recipe->slug) }}" target="_blank">{!! $recipe->title !!}</a>
                            @php
                                $test = app('profanityFilter')->replaceWith('#')->replaceFullWords(false)->filter($recipe->slug, true);

                                if (isset($test['hasMatch'])) {
                                    $matched[] = $test['matched'];
                                }

                                $test = app('profanityFilter')->replaceWith('#')->replaceFullWords(false)->filter($recipe->title, true);

                                if (isset($test['hasMatch'])) {
                                    $matched[] = $test['matched'];
                                }
                            @endphp
                        </div>

                        <div class="col-4">
                            <p>{{ $recipe->description }}</p>
                            @php
                                $test = app('profanityFilter')->replaceWith('#')->replaceFullWords(false)->filter($recipe->description, true);

                                if (isset($test['hasMatch'])) {
                                    $matched[] = $test['matched'];
                                }
                            @endphp
                        </div>

                        <div class="col-5">
                            <ol>
                                @foreach($recipe->directions as $val)
                                    <li class="float-left ml-2">{{ $val }}</li>
                                    @php
                                        $test = app('profanityFilter')->replaceWith('#')->replaceFullWords(false)->filter($val, true);

                                        if (isset($test['hasMatch'])) {
                                            $matched[] = $test['matched'];
                                        }
                                    @endphp
                                @endforeach
                            </ol>
                        </div>

                        <div class="col-1">
                            @if (!empty($matched))
                                @foreach(array_flatten($matched) as $val)
                                    <span class="badge badge-danger">{{ $val }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@stop