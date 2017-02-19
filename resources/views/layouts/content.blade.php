<div class="content">
    <div class="container">
        <div class="row">
        @if(View::hasSection('sidebar-left'))
            <div class="col12 col-md-4">
                @yield('sidebar-left')
            </div>
            <div class="col-12 col-md-8">
                @yield('content')
            </div>
        @elseif(View::hasSection('sidebar-right'))
            <div class="col-12 col-md-8">
                @yield('content')
            </div>
            <div class="col12 col-md-4">
                @yield('sidebar-right')
            </div>
        @else
            <div class="col-12">
                @yield('content')
            </div>
        @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>