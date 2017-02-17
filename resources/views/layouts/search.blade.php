<form class="autocomplete form-inline mt-2 mt-md-0" method="GET" action="{{ route('search') }}">
    <div class="input-group">
        <input type="text" name="search" id="search" class="search form-control" placeholder="Search for...">
        <span class="input-group-btn">
            <button class="btn btn-gray" type="submit">Search</button>
        </span>
    </div>
</form>