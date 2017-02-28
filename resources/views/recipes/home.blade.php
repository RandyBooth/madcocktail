@extends('layouts.master')

@section('title', 'Home')

@section('content')
    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach($recipes as $recipe)
                    @php
                        $class_blur = '';

                        if (!empty($recipe->image)) {
                            $image = $recipe->image;
                            $class_blur = ' image-blur';
                        } else {
                            $image = 'blank.gif';
                        }

                        $image_color = (!empty($recipe->color)) ? 'style="background-color:'.$recipe->color.';" ' : '';
                    @endphp
                <div class="col-12 mb-3 col-md-4">
                    <div class="recipe card">
                        <div class="image">
                            <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
                                <img {!! $image_color !!}class="card-img-top img-fluid{{$class_blur}}" data-original="{{ route('imagecache', ['template' => 'lists', 'filename' => $image]) }}" src="{{ route('imagecache', ['template' => 'lists-tiny', 'filename' => $image]) }}" alt="">
                            </a>
                        </div>
                        <div class="card-block">
                            <a href="{{ route('recipes.show', ['id' => $recipe->slug]) }}">
                                <h4 class="card-title">{{ $recipe->title }}</h4>
                            </a>
                            <p class="card-text">{{ Helper::nl2Empty($recipe->description) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@stop