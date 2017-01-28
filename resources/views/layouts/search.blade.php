<form class="autocomplete" method="POST" action="{{ asset('/') }}">
    {{--<div>Search: {!! Form::text('search', null, array('id' => 'search')) !!}</div>--}}
    <label for="search">Search</label>
    <input type="text" name="search" id="search">
    {{ csrf_field() }}
</form>