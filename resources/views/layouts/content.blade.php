<div id="content" class="content">
    <div class="container">
        @if(View::hasSection('content-top'))
        <div class="row">
            <div class="col-12">
                @yield('content-top')
            </div>
        </div>
        @endif

        <div class="row">
        @if(View::hasSection('sidebar-left'))
            <div class="col-12 col-md-4 col-lg-3">
                @yield('sidebar-left')
            </div>
            <div class="col-12 col-md-8 col-lg-9">
                @yield('content')
            </div>
        @elseif(View::hasSection('sidebar-right'))
            <div class="col-12 col-md-8 col-lg-9">
                @yield('content')
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                @yield('sidebar-right')
            </div>
        @elseif(View::hasSection('content-left'))
            <div class="col-12 col-md-6">
                @yield('content-left')
            </div>
            <div class="col-12 col-md-6">
                @yield('content-right')
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