<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url" content="" />
    <meta property="og:type" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="" />

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
@yield('style')

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

@yield('script-top')
{{--    <script src="{{ asset('js/vendor/modernizr-2.6.2.min.js') }}"></script>--}}
</head>
<body>
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="container">
        @include('layouts.header')

        @include('layouts.search')

        @include('layouts.flash')

        @include('layouts.content')

        @include('layouts.footer')
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('js/vendor/jquery-3.1.1.min.js') }}"><\/script>')</script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/vendor/jquery.autocomplete.min.js') }}"></script>
    {{--<script src="{{ asset('js/plugins.js') }}"></script>--}}
@yield('script-bottom')
    <script src="{{ asset('js/script.js') }}"></script>

     <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        {{--ga('create','UA-XXXXX-X');--}}
        ga('create','UA-91000248-1', 'none');
        ga('send','pageview');
    </script>
</body>
</html>