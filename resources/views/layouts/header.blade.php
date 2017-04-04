<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-toggleable-md navbar-inverse">
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ingredients.index') }}">Ingredients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recipes.index') }}">Recipes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recipes.create') }}">Add Cocktail</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav order-last ml-lg-2">
                        @if (Auth::check())
                            @php $image = (!empty(Auth::user()->image)) ? Auth::user()->image : 'default-user-20170328.jpg'; @endphp
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user-profile.show', Auth::user()->username) }}">
                                    Profile
                                    <span class="nav-item-icon"><img class="image-icon image-icon-small" src="{{ route('imagecache', ['template' => 'user-small', 'filename' => $image]) }}" alt=""></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user-settings.index.edit') }}">@if(Auth::user()->role){{ 'Admin' }}@else{{ 'Settings' }}@endif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endif
                        </ul>

                        @include('layouts.search')
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

@if(View::hasSection('breadcrumb'))
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @yield('breadcrumb')
                </div>
            </div>
        </div>
    </div>
@endif