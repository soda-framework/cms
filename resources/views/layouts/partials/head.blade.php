<?php

$currentCmsTheme = Request::input('theme', Request::cookie('soda-theme', Request::session('soda-theme', 'default')));
$currentCmsTheme = in_array($currentCmsTheme, ['default', 'lime', 'strawberry', 'grape']) ? $currentCmsTheme : 'default';
$version = Soda::getVersion();

?>
@section('head.main')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@stop

@section('head.title')
    <?php $pageTitle = app('soda.interface')->getTitle(); ?>
    <title>{{ $pageTitle ? $pageTitle . ' :: ' : '' }}Soda CMS</title>
@stop

@section('head.meta')
    <link rel="shortcut icon" href="/soda/cms/img/favicon.ico">
    <link rel="icon" href="/soda/cms/img/favicon.ico">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('head.css')
    <link href="/soda/cms/css/plugins.css?v={{ $version }}" rel="stylesheet" media="all">
    <link href="/soda/cms/css/bootstrap.css?v={{ $version }}" rel="stylesheet" media="all">

    <!-- Styles -->
    @if(!Soda::getApplication()->css_url || Soda::getApplication()->append_css)
        <link href="/soda/cms/css/application.css?v={{ $version }}" rel="stylesheet" media="all">
    @endif
    @if($cssUrl = Soda::getApplication()->css_url)
        <link href="{{ $cssUrl }}?v={{ $version }}" rel="stylesheet" media="all">
    @endif

    <link href="/soda/cms/css/fonts.css?v={{ $version }}" rel="stylesheet" media="all">
    <link href="/soda/cms/css/themes/{{ $currentCmsTheme }}.css?v={{ $version }}" rel="stylesheet" media="all">
@stop

@section('head.js')
    <script src="/soda/cms/js/core.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/application.js?v={{ $version }}"></script>
    <script>
        (function (Soda) {
            Soda.queryString = {!! json_encode(Request::query(), JSON_HEX_TAG) !!}
                Soda.urls = {
                sort: '{{ route('soda.sort') }}',
            };
            Soda.lang = {!! json_encode([
                'swal' => [
                    'title' => trans('soda::phrases.are_you_sure'),
                    'text'  => trans('soda::phrases.cannot_reverse'),
                    'confirm' => trans('soda::phrases.yes_delete'),
                    'cancel' => trans('soda::actions.cancel'),
                ]
            ]) !!}
                Soda.theme = '{{ $currentCmsTheme }}';
            return Soda;
        })(Soda || {});

        setTimeout(function () { // Force load body after 5 seconds
            if ((' ' + document.body.className + ' ').indexOf(' loaded ') == -1) document.body.className = document.body.className + ' loaded';
        }, 5000);
    </script>
@stop

@section('head.extra')
@stop

@section('footer.js')
    <script src="/soda/cms/js/forms/slugs.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/forms/dates.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/forms/tinymce.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/forms/upload.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/forms/json.js?v={{ $version }}"></script>
    <script src="/soda/cms/js/forms/multiselect.js?v={{ $version }}"></script>
@stop

@section('head')
    @yield('head.main')
    @yield('head.title')
    @yield('head.meta')
    @yield('head.css')
    @yield('head.js')
    @yield('head.extra')
@show
