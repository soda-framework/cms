<!DOCTYPE html>
<html lang="en">
<head>
    @include("soda::partials.head")
    @yield("head")
    @yield('main-header')
</head>
<body>
    <nav class="navbar navbar-fixed-top navbar-dark bg-inverse top-nav">
        <a class="navbar-brand" href="{{route('home')}}">Soda CMS</a>
        <ul class="nav navbar-nav">

            <li class="nav-item active">
                <a class="nav-link" href="{{route('home')}}">Home</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="/">View Site</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
            @if (Auth::guest())
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/register') }}">Register</a>
                </li>
            @endif
        </ul>
        @if(Auth::check())
        <div class="pull-xs-right">
            <div class="dropdown">
                <a href='#' class="btn btn-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{Auth::user()->name}}</a>

                <div class="dropdown-menu dropdown-menu-right " aria-labelledby="dropdownMenu1">
                    <a class='dropdown-item' href="{{ route('logout') }}"><i class="fa fa-btn fa-users"></i>My Settings</a>
                    <a class='dropdown-item' href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
                </div>
            </div>
        </div>
        @endif
    </nav>
    @yield('main-content')
    @yield("footer.js")
</body>
</html>
