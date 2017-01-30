<form class="autocomplete" method="POST" action="{{ route('search') }}">
    {{ csrf_field() }}

    <label for="search">Search</label>
    <input type="text" name="search" id="search" class="search">

    <button type="submit">Submit</button>
</form>

<form class="select" method="POST" action="{{ asset('/') }}">
    {{ csrf_field() }}

    <label for="search">Search Ingredient</label>
    <select class="search-select" name="ingredient" style="width: 100%">
        <option value="">--</option>
    </select>

    <button type="submit">Submit</button>
</form>