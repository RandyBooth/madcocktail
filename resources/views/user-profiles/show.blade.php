@extends('layouts.master')

@php $author = (!empty($user->display_name)) ? $user->display_name : $user->username; @endphp

@section('title', $author. '\'s Recipes')

@php $random_color[$user->username] = Helper::get_cache_random_color($user->username); @endphp

@section('content-top')
    <div class="row pb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                @if (!empty($user->image))
                    <img class="image-icon d-inline-block" src="{{ route('imagecache', ['template' => 'user-profile', 'filename' => $user->image]) }}" alt="">
                @else
                    <span style="background-color: {{ $random_color[$user->username] }};" class="image-icon d-inline-block"></span>
                @endif

                <h1 class="ml-2">{{ $author }}</h1>
            </div>
        </div>
    </div>
@stop

@php
    $has_about = (!empty($user_settings->about));
    $has_links = (!empty($user_settings->link) || !empty($user_settings->facebook_link) || !empty($user_settings->google_plus_link) || !empty($user_settings->pinterest_link) || !empty($user_settings->twitter_link));
    $size = 'col-12 mb-5 col-md-6 mb-md-4 col-lg-4';
@endphp

@if($has_about || $has_links)
    @php $size = 'col-12 mb-5 col-lg-6 mb-lg-4'; @endphp

    @section('sidebar-left')
        <div class="row mb-4 py-3 mb-md-0 color-background-gray-light">
            @if($has_about)
            <div class="col-12">
                <h4>About Me</h4>

                {!! Helper::nl2p($user_settings->about, false) !!}
            </div>
            @endif

            @if($has_links)
            @php
                $links = [
                    [
                        'title' => 'Website',
                        'icon' => 'globe',
                        'link' => $user_settings->link
                    ],
                    [
                        'title' => 'Facebook',
                        'icon' => 'facebook',
                        'link' => $user_settings->facebook_link
                    ],
                    [
                        'title' => 'Twitter',
                        'icon' => 'twitter',
                        'link' => $user_settings->twitter_link
                    ],
                    [
                        'title' => 'Pinterest',
                        'icon' => 'pinterest-p',
                        'link' => $user_settings->pinterest_link
                    ],
                    [
                        'title' => 'Google+',
                        'icon' => 'google-plus',
                        'link' => $user_settings->google_plus_link
                    ],
                ];
            @endphp
            <div class="col-12">
                <div style="font-size: 24px;" class="row justify-content-center justify-content-md-start">
                    @foreach($links as $link)
                        @if(!empty($link['link']))
                            <div class="col-2"><a href="{{ $link['link'] }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-{{ $link['icon'] }}" title="{{ $link['title'] }}" aria-hidden="true"></i></a></div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @stop
@endif

@section('content')
    @if(!empty($recipes))
    <div class="row">
        <div class="col-12">
            <h3>Recipes</h3>

            <hr>

            <div class="row">
                @foreach($recipes as $recipe)
                <div class="{{ $size }}">
                    @include('layouts.recipe-card', compact('random_color'))
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @include('recipes.footer-option')
@stop