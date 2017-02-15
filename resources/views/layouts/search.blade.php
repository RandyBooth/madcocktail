<form class="autocomplete form-inline mt-2 mt-md-0" method="POST" action="{{ route('search') }}">
    {{ csrf_field() }}
    <input type="text" name="search" id="search" class="search form-control mr-sm-2" placeholder="Search">
    <button class="btn btn-primary my-2 my-sm-0" type="submit">Submit</button>
</form>