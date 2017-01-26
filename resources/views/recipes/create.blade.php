@extends('layouts.master')

@section('style')
<?php /*<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />*/ ?>
@stop

@section('script-bottom')
<?php /*<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>*/ ?>
@stop

@section('content')
    @if (!empty($glasses))
    <div class="form-group">
        <label for="glasses" class="">Glasses</label>

        <div class="col-md-6">
            <select name="glasses" id="glasses">
                @foreach($glasses as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('recipes.store') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="title" class="">Title</label>

            <div class="col-md-6">
                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" {{-- required --}} autofocus>

                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div><button type="submit">Submit</button></div>
    </form>
@stop