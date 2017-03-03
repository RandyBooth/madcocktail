@if(Auth::check())
    @php
        $owner = false;
        $admin = false;

        if (Route::is('recipes.show') && !empty($recipe)) {
            $owner = Helper::is_owner($recipe->user_id);
            $admin = Helper::is_admin();
        }
    @endphp
<div class="row mb-4">
    <div class="col-12 my-3 text-md-right">
@if($admin)
        <form action="{{ route('recipes.destroy', $recipe->token) }}" method="post">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
@endif
            <div class="btn-group btn-group-sm" role="group" aria-label="">
                <a class="btn btn-secondary" href="{{ route('recipes.create') }}"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> New Recipe</a>
@if($owner)
                <a class="btn btn-secondary" href="{{ route('recipes.edit', $recipe->token) }}"><i class="fa fa-pencil mr-1" aria-hidden="true"></i> Edit</a>
@endif
@if($admin)
                <button onclick="return confirm('Are you sure you want to delete?')" type="submit" class="btn btn-secondary"><i class="fa fa-trash-o mr-1" aria-hidden="true"></i> Delete</button>
@endif
            </div>
@if($admin)
        </form>
@endif
    </div>
</div>
@endif