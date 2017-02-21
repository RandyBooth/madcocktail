@if(Helper::is_admin())
<div class="row">
    <div class="col-12 text-right mt-3">
@if(Route::is('ingredients.show'))
        <form action="{{ route('ingredients.destroy', $ingredient->token) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
@endif
            <div class="btn-group btn-group-sm" role="group" aria-label="">
                <a class="btn btn-secondary" href="{{ route('ingredients.create') }}"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> New Ingredient</a>
@if(Route::is('ingredients.show'))
                <a class="btn btn-secondary" href="{{ route('ingredients.edit', $ingredient->token) }}"><i class="fa fa-pencil mr-1" aria-hidden="true"></i> Edit</a>
                <button onclick="return confirm('Are you sure you want to delete?')" type="submit" class="btn btn-secondary"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> Delete</button>
@endif
            </div>
@if(Route::is('ingredients.show'))
        </form>
@endif
    </div>
</div>
@endif