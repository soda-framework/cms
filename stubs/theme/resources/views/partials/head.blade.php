@section('head.main')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@stop

@section('head.title')
    <title>Welcome</title>
@stop

@section('head.meta')
    <link rel="shortcut icon" type="image/ico" href="/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('head.css')
    <!-- Styles -->
    <link href="/themes/soda-example/css/app.css" rel="stylesheet" type="text/css">
@stop

@section('head.js')
    <!-- JavaScripts -->
    <script src="/themes/soda-example/js/vendor.js"></script>
    <script src="/themes/soda-example/js/app.js"></script>
    @if($ga_code = env('GOOGLE_ANALYTICS_CODE'))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '{{ $ga_code }}', 'auto');
            ga('require', 'displayfeatures');
            ga('send', 'pageview');
        </script>
    @endif
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
