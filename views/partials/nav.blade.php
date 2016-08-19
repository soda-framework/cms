<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="soda-logo"  href="{{ route('home') }}"><img src="/sodacms/sodacms/img/sodacms_logowhite.png" /></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a class="" href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="">
                    <a class="" href="/" target="_blank">View Site</a>
                </li>
                @foreach( event(new Soda\Cms\Events\TopNavWasRendered()) as $item)
                    {!! $item !!}
                @endforeach
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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-btn fa-user"></i> {{ Auth::user()->username }}
                    </a>
                    <ul class="dropdown-menu">
                        @foreach( event(new Soda\Cms\Events\TopNavDropdownWasRendered()) as $item)
                            {!! $item !!}
                        @endforeach
                        <li><a class='dropdown-item' href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
