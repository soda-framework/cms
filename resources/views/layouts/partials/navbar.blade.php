<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="soda-logo" href="{{ route('soda.home') }}">
                <img src="/soda/cms/img/sodacms_droplime.png" />
                <img src="/soda/cms/img/sodacms_logowhite.png" />
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div>
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a class="sidebar-toggle" href="#" aria-expanded="false" data-sidebar-toggle>
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </li>
            </ul>
            @if(Soda::auth()->check())
            <ul class="nav navbar-nav navbar-right">
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
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
