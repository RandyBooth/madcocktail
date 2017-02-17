@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
@stop

@section('script-bottom')
    <script src="{{ asset('js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('js/select-ingredients-min.js') }}"></script>
@stop

@section('content')
    <script>
        var drinkMeasures = {!! json_encode($measures) !!};
    </script>

    @yield('form')
        {{ csrf_field() }}
        {!! Honeypot::generate('name', 'my_time') !!}

        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
            <label for="title" class="">Title</label>

            <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $recipe['title']) }}" required  autofocus>

            @if ($errors->has('title'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
            <label for="description" class="">Description</label>

            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $recipe['description']) }}</textarea>

            @if ($errors->has('description'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('ingredients') ? ' has-danger' : '' }}">
            <label for="ingredients" class="">Ingredients</label>

            <div id="select-ingredients">
                <div id="create-ingredients-group">
                @if (!empty(old('ingredients', $recipe['ingredients'])))
                    @php
                        $old_ingredients = old('ingredients', $recipe['ingredients']);
                        $old_ingredients_measure = old('ingredients_measure', $recipe['ingredients_measure']);
                        $old_ingredients_measure_amount = old('ingredients_measure_amount', $recipe['ingredients_measure_amount']);
                    @endphp

                    @foreach($old_ingredients as $token)
                    @if(!empty($token))
                    @if(!empty($ingredients[$token]))
                    <div class="form-inline create-ingredients-div mb-4">
                        <select class="search-select select-ingredients form-control" name="ingredients[]" style="width: 100%">
                            <option value="{{$token}}">{{$ingredients[$token]}}</option>
                        </select>
                        <div class="input-group">
                            <div class="input-group-btn">
                                <input class="select-ingredients-measure-amount form-control" type="text" name="ingredients.measure.amount[]" value="@if(!empty($old_ingredients_measure_amount[$loop->index])){{ $old_ingredients_measure_amount[$loop->index] }}@endif">
                                <select class="select-ingredients-measure form-control" name="ingredients.measure[]">
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
                                <button type="button" class="btn btn-gray create-ingredients-close" href=""><i class="fa fa-times" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @endforeach
                @endif
                </div>

                <button id="create-ingredients-button" class="btn btn-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add Ingredient</button>
            </div>

            @if ($errors->has('ingredients'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('ingredients') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('directions') ? ' has-danger' : '' }}">
            <label for="directions" class="">Directions</label>

            <textarea name="directions" id="directions" class="form-control" rows="4">{{ old('directions', $recipe['directions']) }}</textarea>

            @if ($errors->has('directions'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('directions') }}</strong>
                </span>
            @endif
        </div>

        @if (!empty($glasses))
        <div class="form-group{{ $errors->has('glass') ? ' has-danger' : '' }}">
            <label for="glass" class="">Glass Type</label>

            <select name="glass" id="glass" class="form-control">
                <option value="">--</option>
            @foreach($glasses as $key => $val)
                <option value="{{ $key }}"@if(old('glass', $recipe['glass']) == $key){{ ' selected' }}@endif>{{ $val }}</option>
            @endforeach
            </select>

            @if ($errors->has('glass'))
                <span class="form-control-feedback">
                    <strong>{{ $errors->first('glass') }}</strong>
                </span>
            @endif
        </div>
        @endif

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
@stop