<form class="autocomplete" method="POST" action="{{ route('search') }}">
    {{ csrf_field() }}

    <label for="search">Search</label>
    <input type="text" name="search" id="search" class="search">

    <button class="btn btn-primary" type="submit">Submit</button>
</form>