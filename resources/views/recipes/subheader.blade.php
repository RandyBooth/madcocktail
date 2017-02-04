@if (Auth::check())
<div class="row">
    <div class="six columns">
        <a href="{{ route('recipes.create') }}">Create</a>
        @if(Route::is('recipes.show') && Helper::is_owner($recipe->user_id))
            <span> | </span>
            <a href="{{ route('recipes.edit', $recipe->token) }}">Edit</a>
        @endif
    </div>
</div>
@endif