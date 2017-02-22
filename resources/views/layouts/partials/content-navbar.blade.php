<nav class="content-navbar navbar navbar-default">
    <div class="container-fluid">
        <div class="nav navbar-nav navbar-left navbar-breadcrumb">
            @yield('breadcrumb')
        </div>
        @if(Soda::auth()->check())
            <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="hidden-xs">
                            <i class="fa fa-btn fa-user"></i>
                            <span>{{ Soda::auth()->user()->username }}</span>
                        </span>
                        <span class="visible-xs">
                            <i class="fa fa-cogs"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class='dropdown-item' href="/" target="_blank">
                                <i class="fa fa-eye"></i>
                                <span>View Site</span>
                            </a>
                        </li>
                        @permission('view-drafts')
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.toggle-draft') }}">
                                <i class="fa fa-btn fa-pencil"></i>
                                <span>{{ Session::get("soda.draft_mode") == true ? "Live" : "Draft" }} Mode</span>
                            </a>
                        </li>
                        @endpermission
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.logout') }}">
                                <i class="fa fa-btn fa-sign-out"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </div>
</nav>
