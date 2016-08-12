<!DOCTYPE html>
<html lang="en" class="{{@$html_class}}">
<head>
    @include("soda::partials.head")
    @yield('main-header')
</head>
<body class="{{@$body_class}}">
    <div class="container-fluid">

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="soda-logo"  href="{{ route('home') }}"><img src="/sodacms/sodacms/img/sodacms_logowhite.png" /></a>
                </div>



                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">

                        <li class=" active">
                            <a class="" href="{{ route('home') }}">Dashboard</a>
                        </li>
                        <li class="">
                            <a class="" href="/" target="_blank">View Site</a>
                        </li>
                        @foreach( event(new Soda\Events\TopNavWasRendered()) as $item)
                            {!! $item !!}
                        @endforeach
                        {{--
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        --}}
                        @if (Auth::guest())
                            <li class="">
                                <a class="" href="{{ url('/login') }}">Login</a>
                            </li>
                            {{--
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/register') }}">Register</a>
                            </li>
                            --}}
                        @endif
                    </ul>
                    @if(Auth::check())
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href='#' class="btn btn-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-btn fa-user"></i> {{ Auth::user()->username }}</a>
                                <ul class="dropdown-menu">
                                    @foreach( event(new Soda\Events\TopNavDropdownWasRendered()) as $item)
                                        {!! $item !!}
                                    @endforeach
                                    <li><a class='dropdown-item' href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>
@yield('main-content')
</div>
@yield("footer.js")
</body>
</html>
