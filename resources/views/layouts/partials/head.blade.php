<?php $currentCmsTheme = Request::input('theme', 'lime'); ?>
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
    <link href="/soda/cms/css/bootstrap.css" rel="stylesheet" media="all">

    <!-- Styles -->
    @if(!Soda::getApplication()->css_url || Soda::getApplication()->append_css)
        <link href="/soda/cms/css/application.css" rel="stylesheet" media="all">
    @endif
    @if($cssUrl = Soda::getApplication()->css_url)
        <link href="{{ $cssUrl }}" rel="stylesheet" media="all">
    @endif

    <link href="/soda/cms/css/fonts.css" rel="stylesheet" media="all">
    <link href="/soda/cms/css/plugins.css" rel="stylesheet" media="all">
    <link href="/soda/cms/css/themes/{{ $currentCmsTheme }}.css" rel="stylesheet" media="all">
@stop

@section('head.js')
    <script src="/soda/cms/js/core.js"></script>
    <script src="/soda/cms/js/application.js"></script>
    <script>
        (function (Soda) {
            Soda.queryString = {!! json_encode(Request::query(), JSON_HEX_TAG) !!}
            Soda.urls = {
                sort: '{{ route('soda.sort') }}',
            };
            Soda.theme = '{{ $currentCmsTheme }}';
            return Soda;
        })(Soda || {});
    </script>
@stop

@section('head.extra')
@stop

@section('footer.js')
    <script src="/soda/cms/js/forms/slugs.js"></script>
    <script src="/soda/cms/js/forms/dates.js"></script>
    <script src="/soda/cms/js/forms/tinymce.js"></script>
    <script src="/soda/cms/js/forms/upload.js"></script>
    <script src="/soda/cms/js/forms/json.js"></script>
    <script src="/soda/cms/js/forms/multiselect.js"></script>
@stop

@section('head')
    @yield('head.main')
    @yield('head.title')
    @yield('head.meta')
    @yield('head.css')
    @yield('head.js')
    @yield('head.extra')
@show
