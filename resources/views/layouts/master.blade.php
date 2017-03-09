<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
    <meta property="og:url" content="@yield('og-url', url()->full())" />
    {{--<meta property="og:type" content="@yield('og-type')" />--}}
    <meta property="og:title" content="@yield('og-title')" />
    <meta property="og:description" content="@yield('og-description')" />
    <meta property="og:image" content="@yield('og-image')" />

    <meta name="twitter:card" content="summary">
    {{--<meta name="twitter:site" content="@MadCocktail" />--}}
    <meta name="twitter:url" content="@yield('og-url', url()->full())">
    <meta name="twitter:description" content="@yield('og-description')">
    <meta name="twitter:image" content="@yield('og-image')">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Oxygen:700|Source+Sans+Pro:400{{--,400i--}},600" rel="stylesheet">

    <link rel="stylesheet" href="{{ Bust::url('/css/vendor/bootstrap.css', true) }}">
    {{--<link rel="stylesheet" href="{{ Bust::url('/css/vendor/font-awesome.css', true) }}">--}}
@yield('style')
    <link rel="stylesheet" href="{{ Bust::url('/css/style.css', true) }}">

    <link rel="shortcut icon" href="{{ Bust::url('/favicon.ico', true) }}">

@yield('script-top')
</head>
<body>

    @include('layouts.header')

    @include('layouts.alert')

    @include('layouts.content')

    @include('layouts.footer')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ Bust::url('/js/vendor/jquery-3.1.1.min.js', true) }}"><\/script>')</script>
    <script src="{{ Bust::url('/js/plugins-min.js', true) }}"></script>
    <script src="{{ Bust::url('/js/vendor/bootstrap.min.js', true) }}"></script>
    <script src="{{ Bust::url('/js/script-min.js', true) }}"></script>
@yield('script-bottom')

    @if(!empty(config('services.google.analytics')))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ config('services.google.analytics') }}', 'auto');
        ga('send', 'pageview');
    </script>
    @endif
</body>
</html>