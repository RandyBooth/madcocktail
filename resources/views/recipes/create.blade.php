@extends('layouts.default')

@section('style')
<?php /*<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />*/ ?>
@stop

@section('script-bottom')
<?php /*<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>*/ ?>
@stop

@section('content')

    @foreach ($errors->all() as $error)
    <p class="error">{{ $error }}</p>
    @endforeach

    {!! $glasses !!}
    <?php
        $select_glasses = [];
        foreach($glasses as $glass) {
            $select_glasses[$glass['token']] = $glass['title'];
        }
    ?>

    @if (isset($recipe))
        {!! Form::model($recipe, array('route' => array('r.update', $recipe->token), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'r.store')) !!}
    @endif

        <div>{!! Form::text('title') !!}</div>
        <div>{!! Form::textarea('description') !!}</div>
        <div>{!! Form::text('instruction', null, array('id' => 'instruction')) !!}</div>
        <div>Category: {!! Form::text('category', null, array('id' => 'category')) !!}</div>
        <div>{!! Form::select('glasses', $select_glasses) !!}</div>
        {!! Form::honeypot('drink_type', 'drink_date') !!}
        <div>{!! Form::submit('Submit') !!}</div>

    {!! Form::close() !!}

@stop