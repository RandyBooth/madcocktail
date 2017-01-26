<div style="margin: 20px 0;" class="row">
    @if(Session::has('success'))
        <div style="background-color: green; color: white; padding: 5px 20px;">{{ Session::get('success') }}</div>
    @elseif(Session::has('warning'))
        <div style="background-color: red; color: white; padding: 5px 20px;">{{ Session::get('warning') }}</div>
    @endif
</div>