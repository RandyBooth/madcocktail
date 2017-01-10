<div class="row">
    <div class="six columns">
    @if (Auth::check())
        <a href="{{ route('ingredients.create') }}">Create</a>
    @endif
    </div>
</div>