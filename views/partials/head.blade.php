@section('head.main')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@stop

@section('head.title')
    <title>Soda CMS</title>
@stop

@section('head.meta')
    <link rel="shortcut icon" type="image/ico" href="/soda/soda/img/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('head.css')
    <!-- Styles -->
    <link href="/sodacms/sodacms/css/application.min.css" rel="stylesheet">
@stop

@section('head.js')
    <!-- JavaScripts -->
    <script src="/sodacms/sodacms/js/core.min.js"></script>
    {{-- TODO: move to only pages featuring forms --}}
    <script src="/sodacms/sodacms/js/forms.min.js"></script>
    <script src="/sodacms/sodacms/js/application.min.js"></script>
@stop

@section('head.extra')
@stop

@section('footer.js')
@stop

@section('head')
    @yield('head.main')
    @yield('head.title')
    @yield('head.meta')
    @yield('head.css')
    @yield('head.js')
    @yield('head.extra')
@show
