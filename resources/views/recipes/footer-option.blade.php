@if(Helper::is_owner($recipe->user_id))
<div class="row">
    <div class="col-12 my-3 text-md-right">
@if(Route::is('recipes.show') && Helper::is_admin())
        <form action="{{ route('recipes.destroy', $recipe->token) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
@endif
            <div class="btn-group btn-group-sm" role="group" aria-label="">
                <a class="btn btn-secondary" href="{{ route('recipes.create') }}"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> New Recipe</a>
@if(Route::is('recipes.show'))
                <a class="btn btn-secondary" href="{{ route('recipes.edit', $recipe->token) }}"><i class="fa fa-pencil mr-1" aria-hidden="true"></i> Edit</a>
@endif
@if(Route::is('recipes.show') && Helper::is_admin())
                <button onclick="return confirm('Are you sure you want to delete?')" type="submit" class="btn btn-secondary"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> Delete</button>
@endif
            </div>
@if(Route::is('recipes.show') && Helper::is_admin())
        </form>
@endif
    </div>
</div>
@endif