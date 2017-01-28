@if (Auth::check())
<div class="row">
    <div class="six columns">
            <a href="{{ route('recipes.create') }}">Create</a>
            @if(Route::is('recipes.show') && ($recipe->user_id == 0 || $recipe->user_id == Auth::id()))
                <span> | </span>
                <a href="{{ route('recipes.edit', $recipe->token) }}">Edit</a>
            @endif
    </div>
</div>
@endif