<!DOCTYPE html>
<html lang="en" class="{{@$html_class}}">
<head>
    @include(soda_cms_view_path('layouts.partials.head'))
</head>
<body class="{{@$body_class}}">
    <div class="soda-wrapper container-fluid">
        @include(soda_cms_view_path('layouts.partials.navbar'))
        @yield('main-content')
    </div>
    @yield("footer.js")
</body>
</html>
