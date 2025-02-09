@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="{{ Bust::url('/css/vendor/select2-bootstrap.css', true) }}">
@stop

@section('script-bottom')
    <script src="{{ Bust::url('/js/vendor/select2.min.js', true) }}"></script>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            @yield('form')
                {{ csrf_field() }}
                {!! Honeypot::generate('first_name', 'my_time') !!}

                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="title" class="">Title</label>

                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $ingredient['title']) }}" required autofocus>

                    @if ($errors->has('title'))
                        <div class="form-control-feedback">
                            <strong>{{ $errors->first('title') }}</strong>
                        </div>
                    @endif
                </div>

                @if (!empty($ingredients))
                <div class="form-group{{ $errors->has('ingredients') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="ingredients" class="">Parent of ingredient</label>

                    <select style="width: 100%;" class="form-control select2-set" name="ingredients" id="ingredients">
                        @foreach($ingredients as $key => $val)
                            <option value="{{ $key }}"@if(old('ingredients', $ingredient['ingredients']) == $key){{ 'selected' }}@endif @if(isset($ingredient['token'])) @if($ingredient['token'] == $key){{ 'disabled' }}@endif @endif>{{ $val }}</option>
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
        </div>
    </div>
@stop