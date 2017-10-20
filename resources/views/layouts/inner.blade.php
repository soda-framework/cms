@extends(soda_cms_view_path('layouts.app'))

@section('main-content')
    <div class="row">
        @include(soda_cms_view_path('layouts.partials.mobile-header'))
    </div>
    @include(soda_cms_view_path('layouts.partials.sidebar'))
    <div class="sidebar-offset">
        @include(soda_cms_view_path('layouts.partials.content-navbar'))
        <div class="main-content animated animate-on-unload">
            <div class="container-fluid">
                @section('main-content-outer')
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="content-header clearfix">
                                <div class="pull-left">
                                    <h1>
                                        @if($icon = app('soda.interface')->getHeadingIcon())
                                            <i class="{{ $icon }}"></i>
                                        @endif
                                        <span>{{ app('soda.interface')->getHeading() }}</span>
                                    </h1>
                                    @if($description = app('soda.interface')->getDescription())
                                        <p class="text-muted">{{ $description }}</p>
                                    @endif

                                    @section('breadcrumb')
                                        <ul class="breadcrumb">
                                            <?php $breadcrumbLinks = app('soda.interface')->breadcrumbs()->getLinks(); ?>
                                            @foreach($breadcrumbLinks as $breadcrumbLink)
                                                <li>
                                                    <a href="{{ $breadcrumbLink['url'] }}">{{ $breadcrumbLink['title'] }}</a>
                                                </li>
                                            @endforeach
                                            @if(count($breadcrumbLinks) || app('soda.interface')->getTitle() != app('soda.interface')->getHeading())
                                                <li class="active">{{ app('soda.interface')->getTitle() }}</li>
                                            @endif
                                        </ul>
                                    @stop
                                    @yield('breadcrumb')
                                </div>

                                <div class="pull-right content-heading-button">
                                    @yield('content-heading-button')
                                </div>
                            </div>
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
        <div class="soda-powered-by">@lang('soda::misc.powered_by', ['version' => $version ? " (version $version)" : ""])</div>
    </div>
@endsection
