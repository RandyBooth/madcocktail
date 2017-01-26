@extends('layouts.master')

@section('title', 'Create New Ingredient')

@section('content')
    <form method="POST" action="{{ route('ingredients.store') }}">
        {{ csrf_field() }}

        @if (!empty($ingredients))
        <div class="form-group">
            <label for="ingredients" class="">Parent</label>

            <div class="col-md-6">
                <select name="ingredients" id="ingredients">
                    @foreach($ingredients as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

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