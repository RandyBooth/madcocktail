<div style="margin: 20px 0;">
    !! HEADER !!

    @if (Auth::check())
        {{ 'ID: '.Auth::user()->id . ' ||| ' . Auth::user()->email }}
        <a href="{{ route('logout') }}">Logout</a>
    @else
        <a href="{{ route('login') }}">Login</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}">Facebook</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}">Google</a> |
        <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}">Twitter</a>
    @endif
</div>