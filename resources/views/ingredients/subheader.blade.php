@if (Auth::check())
<div class="row">
    <div class="six columns">
        <a href="{{ route('ingredients.create') }}">Create</a>
        @if(Route::is('ingredients.show') && Helper::is_owner($ingredient->user_id))
            <span> | </span>
            <a href="{{ route('ingredients.edit', $ingredient->token) }}">Edit</a>
        @endif
    </div>
</div>
@endif