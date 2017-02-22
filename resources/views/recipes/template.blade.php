@extends('layouts.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap.css') }}">
@stop

@section('script-bottom')
    <script src="{{ asset('js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('js/select-ingredients-min.js') }}"></script>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-9 col-xl-7">
            <script>
                var drinkMeasures = {!! json_encode($measures) !!};
            </script>

            @yield('form')
                {{ csrf_field() }}
                {!! Honeypot::generate('name', 'my_time') !!}

                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="title" class="">Title</label>

                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $recipe['title']) }}" required  autofocus>

                    @if ($errors->has('title'))
                        <span class="form-control-feedback">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="description" class="">Description</label>

                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $recipe['description']) }}</textarea>

                    @if ($errors->has('description'))
                        <span class="form-control-feedback">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('ingredients') ? ' has-danger' : '' }}">
                    <div class="row hidden-md-down">
                        <div class="col-6">
                            <label class="form-control-label">Ingredients</label>
                        </div>
                        <div class="col-2">
                            <label class="form-control-label">Quality</label>
                        </div>
                        <div class="col-4">
                            <label class="form-control-label">Unit</label>
                        </div>
                    </div>

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
                            <div class="row mb-4 mb-lg-0 create-ingredients-div">
                                <div class="col-12 mb-2 col-lg-6 mb-lg-4 pr-lg-0">
                                    <label class="form-control-label hidden-lg-up">Ingredients</label>
                                    <select style="width: 100%" class="select-ingredients form-control" name="ingredients[]">
                                        <option value="{{$token}}">{{$ingredients[$token]}}</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-2 col-lg-2 mb-lg-4 pr-lg-0">
                                    <label class="form-control-label hidden-lg-up">Quality</label>
                                    <input class="select-ingredients-measure-amount form-control" type="text" name="ingredients.measure.amount[]" value="@if(!empty($old_ingredients_measure_amount[$loop->index])){{ $old_ingredients_measure_amount[$loop->index] }}@endif">
                                </div>
                                <div class="col-12 mb-2 col-lg-3 mb-lg-4 pr-lg-0">
                                    <label class="form-control-label hidden-lg-up">Unit</label>
                                    <select style="width: 100%" class="select-ingredients-measure form-control" name="ingredients.measure[]">
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
                                </div>
                                <div class="col-12 mb-2 col-lg-1 mb-lg-4 align-self-end">
                                    <button type="button" class="btn btn-gray create-ingredients-close"><i class="fa fa-times" aria-hidden="true"></i></button>
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
                    <label class="form-control-label" for="directions" class="">Directions</label>

                    <textarea name="directions" id="directions" class="form-control" rows="4">{{ old('directions', $recipe['directions']) }}</textarea>

                    @if ($errors->has('directions'))
                        <span class="form-control-feedback">
                            <strong>{{ $errors->first('directions') }}</strong>
                        </span>
                    @endif
                </div>

                @if (!empty($glasses))
                <div class="form-group{{ $errors->has('glass') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="glass" class="">Glass Type</label>

                    <select style="width: 100%" name="glass" id="glass" class="select2-set form-control">
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
        </div>
    </div>
@stop