<div style="margin: 20px 0;" class="row">
    <div class="six columns">
        <a href="{{ url('/') }}">Home</a>
        <span> | </span>
        <a href="{{ route('ingredients.index') }}">Ingredients</a>
        <span> | </span>
        <a href="{{ route('recipes.index') }}">Recipes</a>
    </div>

    <div class="six columns">
    @if (Auth::check())
            <span>ID: {{ Auth::user()->id }}</span>
            <span> | </span>
            <span>Role: {{ Auth::user()->role_type }}</span>
            <span> | </span>
            <span><a href="{{-- route('users.show') --}}">{{ Auth::user()->email }}</a></span>
        <span> | </span>
        <a href="{{ url('/logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @else
        <a href="{{ route('login') }}">Login</a>
        <span> | </span>
        <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}">Facebook</a>
        <span> | </span>
        <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}">Google</a>
        <span> | </span>
        <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}">Twitter</a>
    @endif
    </div>
</div>