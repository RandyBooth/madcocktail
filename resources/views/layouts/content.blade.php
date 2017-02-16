<div class="content">
    <div class="container">
        <div class="row">
        @if(View::hasSection('sidebar'))
            <div class="col-8">
                @yield('content')
            </div>
            <div class="col-4">
                @yield('sidebar')
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