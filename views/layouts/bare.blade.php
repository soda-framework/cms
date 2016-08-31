<!DOCTYPE html>
<html lang="en" class="{{@$html_class}}">
<head>
    @include(soda_cms_view_path('partials.head'))
</head>
<body class="{{@$body_class}}">
    <div class="soda-wrapper container-fluid">
        @yield('main-content')
    </div>
    @yield("footer.js")
</body>
</html>
