<form id="form-search" class="autocomplete form-inline mt-2 mt-md-0" method="POST" action="{{ route('search') }}">
    {{ csrf_field() }}

    <div class="hidden-xs-up">
        <input type="text" name="search-id" id="search-id" class="form-control">
        <input type="text" name="search-group" id="search-group" class="form-control">
    </div>

    <div class="input-group">
        <input type="text" name="search" id="search" class="search form-control" placeholder="Search for...">
        <span class="input-group-btn">
            <button class="btn btn-gray" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
        </span>
    </div>
</form>