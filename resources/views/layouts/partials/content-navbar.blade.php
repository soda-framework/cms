<nav class="navbar navbar-default content-navbar">
    <div class="container-fluid">
        @if(Soda::auth()->check())
            <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
                <li>
                    <a href="#">
                        <i class="mdi mdi-account-circle"></i>
                        <span>{{ Soda::auth()->user()->username }}</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-settings"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class='dropdown-item' href="/" target="_blank">
                                <i class="mdi mdi-eye"></i>
                                <span>View Site</span>
                            </a>
                        </li>
                        <li>
                            <a class='dropdown-item' href="#" data-toggle="modal" data-target="#newQuicklinkModal">
                                <i class="mdi mdi-share"></i>
                                <span>Quick link</span>
                            </a>
                        </li>
                        @permission('view-drafts')
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.toggle-draft') }}">
                                <i class="mdi fa-btn mdi-pencil"></i>
                                <span>{{ Session::get("soda.draft_mode") == true ? "Live" : "Draft" }} Mode</span>
                            </a>
                        </li>
                        @endpermission
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.logout') }}">
                                <i class="mdi fa-btn mdi-logout"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </div>
</nav>
