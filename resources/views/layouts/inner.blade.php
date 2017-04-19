@extends(soda_cms_view_path('layouts.app'))

@section('main-content')
    <div class="row">
        @include(soda_cms_view_path('layouts.partials.mobile-header'))
    </div>
    @include(soda_cms_view_path('layouts.partials.sidebar'))
    <div class="sidebar-offset">
        @include(soda_cms_view_path('layouts.partials.content-navbar'))
        <div class="main-content animated fadeInDown animate-on-unload">
            <div class="container-fluid">
                @section('main-content-outer')
                    <div class="row">
                        <div class="col-xs-12">
                            @yield('content-heading')
                        </div>
                    </div>
                    @include(soda_cms_view_path('layouts.partials.alert'))
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="main-content-inner">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                @show
            </div>
        </div>

        <?php $version = Soda::getVersion(); ?>
        <div class="soda-powered-by">Powered by Soda Framework{{ $version ? " (version $version)" : "" }}</div>
    </div>
@endsection
