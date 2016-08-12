<!DOCTYPE html>
<html lang="en" class="{{@$html_class}}">
<head>
    @include(config('soda.hint_path')."::partials.head")
    @yield('main-header')
</head>
<body class="{{@$body_class}}">
    <div class="soda-wrapper container-fluid">
        @yield('main-content')
    </div>
    @yield("footer.js")
</body>
</html>
