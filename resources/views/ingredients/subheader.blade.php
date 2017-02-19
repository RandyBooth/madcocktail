@if(Auth::check())
@if(Helper::is_admin())
<div class="row">
    <div class="col-12 col-md-6">
        <div class="btn-group" role="group" aria-label="">
            <a class="btn btn-primary" href="{{ route('ingredients.create') }}"><i class="fa fa-pencil mr-1" aria-hidden="true"></i> New Ingredient</a>
            {{--@if(Route::is('ingredients.show') && Helper::is_owner($ingredient->user_id))--}}
            {{--<a class="btn btn-gray" href="{{ route('ingredients.edit', $ingredient->token) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>--}}
            {{--@endif--}}
        </div>
    </div>
</div>
@endif
@endif