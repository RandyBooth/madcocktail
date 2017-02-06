@if(Auth::check())
@if(Helper::is_admin())
<div class="row">
    <div class="col-12 col-md-6">
        <a href="{{ route('ingredients.create') }}">Create</a>
        @if(Route::is('ingredients.show') && Helper::is_owner($ingredient->user_id))
            <span> | </span>
            <a href="{{ route('ingredients.edit', $ingredient->token) }}">Edit</a>
        @endif
    </div>
</div>
@endif
@endif