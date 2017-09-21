<nav class="navbar navbar-default content-navbar">
    <div class="container-fluid">
            <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
                @if(Soda::auth()->check())
                <li>
                    <a href="#">
                        <i class="mdi mdi-account-circle"></i>
                        <span>{{ Soda::auth()->user()->username }}</span>
                    </a>
                </li>
                @endif
                @if(count(Soda::getLocales()) > 1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-earth"></i>
                            <span>{{ Soda::getLocale() }}</span>
                        </a>

                        <form method="POST" action="{{ route('soda.language') }}">
                            {!! csrf_field() !!}
                            <ul class="dropdown-menu">
                                @foreach(Soda::getLocales() as $locale => $localeName)
                                    <li>
                                        <button class="btn-link dropdown-item" href="/" target="_blank" name="language" value="{{ $locale }}">
                                            <span>{{ $localeName }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </form>
                    </li>
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-settings"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class='dropdown-item' href="/" target="_blank">
                                <i class="mdi mdi-eye"></i>
                                <span>@langtest('soda::misc.view_site')</span>
                            </a>
                        </li>
                        <li>
                            <a class='dropdown-item' href="#" data-toggle="modal" data-target="#newQuicklinkModal">
                                <i class="mdi mdi-share"></i>
                                <span>@langtest('soda::misc.quicklink_this_page')</span>
                            </a>
                        </li>
                        @permission('view-drafts')
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.toggle-draft') }}">
                                <i class="mdi fa-btn mdi-pencil"></i>
                                <span>{{ Session::get("soda.draft_mode") == true ? trans('soda::misc.live_mode') : trans('soda::misc.draft_mode') }}</span>
                            </a>
                        </li>
                        @endpermission
                        <li>
                            <a class='dropdown-item' href="{{ route('soda.logout') }}">
                                <i class="mdi fa-btn mdi-logout"></i>
                                <span>@langtest('soda::actions.logout')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
    </div>
</nav>
