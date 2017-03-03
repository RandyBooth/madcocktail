@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4>{!! trans('laravel-user-verification::user-verification.verification_error_header') !!}</h4>
            <strong>{!! trans('laravel-user-verification::user-verification.verification_error_message') !!}</strong>
        </div>

        <div class="col-12 mt-4">
            <a href="{{url('/')}}" class="btn btn-primary">
                {!! trans('laravel-user-verification::user-verification.verification_error_back_button') !!}
            </a>
        </div>
    </div>
@endsection
