<nav class="navbar navbar-inverse mobile-header">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="soda-logo-block" href="{{ route('soda.home') }}">
                @include(soda_cms_view('layouts.partials.mobile-header'))
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <ul class="nav navbar-nav navbar-left">
            <li>
                <a class="sidebar-toggle" href="#" aria-expanded="false" data-sidebar-toggle>
                    <i class="glyphicon glyphicon-menu-hamburger"></i>
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </li>
        </ul>
        @if(Soda::auth()->check())
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-settings"></i>
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
    </div><!-- /.container-fluid -->
</nav>
