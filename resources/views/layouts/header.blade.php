<div style="margin: 20px 0;" class="row">
    <div class="six columns">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ route('ingredients.index') }}">Ingredients</a>
        <a href="{{ route('recipes.index') }}">Recipes</a>
    </div>

    <div class="six columns">
    @if (Auth::check())
        {{ 'ID: '.Auth::user()->id . ' ||| ' . Auth::user()->email }}
        <a href="{{ url('/logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @else
        <a href="{{ route('login') }}">Login</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}">Facebook</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}">Google</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}">Twitter</a>
    @endif
    </div>
</div>