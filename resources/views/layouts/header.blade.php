<nav class="navbar navbar-toggleable-md mb-4">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ url('/') }}">Drink Recipes</a>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ingredients.index') }}">Ingredients</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('recipes.index') }}">Recipes</a>
            </li>
        </ul>

        @include('layouts.search')

        <ul class="navbar-nav">
        @if (Auth::check())
            @if(Auth::user()->role_type)
            <span class="navbar-text">
                Role: {{ Auth::user()->role_type }}
            </span>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{-- route('users.show') --}}">{{ Auth::user()->email }}</a>
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
    </div>
</nav>