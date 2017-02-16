@extends('layouts.master')

@section('content')
    @yield('form')
        {{ csrf_field() }}
        {!! Honeypot::generate('name', 'my_time') !!}

        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
            <label for="title" class="">Title</label>

            <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $ingredient['title']) }}" required autofocus>

            @if ($errors->has('title'))
                <div class="form-control-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </div>
            @endif
        </div>

        @if (!empty($ingredients))
        <div class="form-group{{ $errors->has('ingredients') ? ' has-danger' : '' }}">
            <label for="ingredients" class="">Parent of ingredient</label>

            <select class="form-control" name="ingredients" id="ingredients">
                @foreach($ingredients as $key => $val)
                    <option value="{{ $key }}"@if(old('ingredients', $ingredient['ingredients']) == $key) {{ 'selected' }} @endif>{{ $val }}</option>
                @endforeach
            </select>

            @if ($errors->has('ingredients'))
                <div class="form-control-feedback">
                    <strong>{{ $errors->first('ingredients') }}</strong>
                </div>
            @endif
        </div>
        @endif

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
@stop