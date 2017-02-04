@extends('layouts.master')

@section('title', 'Create New Recipe')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
@stop

@section('script-bottom')
    <script src="{{ asset('js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('js/select-ingredients.js') }}"></script>
@stop

@section('content')
    <script>
        var drinkMeasures = {!! json_encode($measures) !!};
    </script>

    <form method="POST" action="{{ route('recipes.store') }}">
        {{ csrf_field() }}
        {!! Honeypot::generate('name', 'my_time') !!}

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

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="">Description</label>

            <div class="col-md-6">
                <textarea name="description" id="description" class="form-control" rows="10">{{ old('description') }}</textarea>

                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('directions') ? ' has-error' : '' }}">
            <label for="directions" class="">Directions</label>

            <div class="col-md-6">
                <textarea name="directions" id="directions" class="form-control" rows="10">{{ old('directions') }}</textarea>

                @if ($errors->has('directions'))
                    <span class="help-block">
                        <strong>{{ $errors->first('directions') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('ingredients') ? ' has-error' : '' }}">
            <label for="ingredients" class="">Ingredients</label>

            <div class="col-md-6">
                <div id="select-ingredients">
                    <div id="create-ingredients-group">
                    @if (!empty(old('ingredients')))
                        @php
                            $old_ingredients = old('ingredients');
                            $old_ingredients_measure = old('ingredients_measure');
                            $old_ingredients_measure_amount = old('ingredients_measure_amount');
                        @endphp

                        @foreach($old_ingredients as $token)
                        @if(!empty($token))
                        @if (!empty($ingredients[$token]))
                        <div class="create-ingredients-div">
                            <a class="create-ingredients-close" href="">X</a>
                            <select class="search-select select-ingredients" name="ingredients[]" style="width: 100%">
                                <option value="{{$token}}">{{$ingredients[$token]}}</option>
                            </select>
                            <select class="select-ingredients-measure" name="ingredients.measure[]">
                                <option value="">--</option>
                                @php
                                    $ingredients_measure = (!empty($old_ingredients_measure[$loop->index])) ? $old_ingredients_measure[$loop->index] : '';
                                @endphp

                                @foreach($measures as $key => $val)
                                    @php
                                    $selected = '';
                                    if ($ingredients_measure == $key)
                                    $selected = ' selected';
                                    @endphp
                                    <option value="{{ $key }}"{{ $selected }}>{{ $val }}</option>
                                @endforeach
                            </select>
                            <input class="select-ingredients-measure-amount" type="text" name="ingredients.measure.amount[]" value="@if(!empty($old_ingredients_measure_amount[$loop->index])){{ $old_ingredients_measure_amount[$loop->index] }}@endif">
                        </div>
                        @endif
                        @endif
                        @endforeach
                    @endif
                    </div>

                    <button id="create-ingredients-button" type="button">Add</button>
                </div>

                @if ($errors->has('ingredients'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ingredients') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        @if (!empty($glasses))
        <div class="form-group">
            <label for="glass" class="">Glass Type</label>

            <div class="col-md-6">
                <select name="glass" id="glass">
                    <option value="">--</option>
                @foreach($glasses as $key => $val)
                    <option value="{{ $key }}"@if(old('glass') == $key){{ ' selected' }}@endif>{{ $val }}</option>
                @endforeach
                </select>

                @if ($errors->has('glass'))
                    <span class="help-block">
                        <strong>{{ $errors->first('glass') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @endif

        <div><button type="submit">Submit</button></div>
    </form>
@stop