@extends('layouts.master')

@section('sidebar-left')
    <div class="row">
        <div class="col-12">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ active('user-settings.email.edit') }}" href="{{ route('user-settings.email.edit') }}">E-mail</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ active('user-settings.password.edit') }}" href="{{ route('user-settings.password.edit') }}">Password</a>
                </li>
            </ul>
        </div>
    </div>
@stop