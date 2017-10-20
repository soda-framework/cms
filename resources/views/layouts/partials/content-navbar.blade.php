<nav class="navbar navbar-default content-navbar">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
            @if(Soda::auth()->check())
                @if(Soda::auth()->user()->application_id === null)
                    <?php $applications = Soda\Cms\Database\Models\Application::where('id', '!=', Soda::getApplication()->id)->with([
                        'urls' => function ($sq) {
                            $sq->where('primary', 1);
                        },
                    ])->get(); ?>

                    @if(count($applications))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-home"></i>
                                <span>{{ Soda::getApplication()->name }}</span>
                            </a>

                            <form method="POST" action="{{ route('soda.switch-application') }}">
                                {!! csrf_field() !!}
                                <ul class="dropdown-menu">
                                    @foreach($applications as $application)
                                        <li>
                                            <button class="btn-link dropdown-item" type="submit" name="redirect" value="{{ str_replace(Soda::getApplicationUrl()->domain, $application->urls->first()->domain, url()->current()) }}">
                                                <span>{{ $application->name }}</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </form>
                        </li>
                    @endif
                @endif
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
            @if(Soda::auth()->check())
                <li>
                    <a href="#">
                        <?php $emailHash = hash('md5', strtolower(trim(Soda::auth()->user()->email))); ?>
                        <img src="https://www.gravatar.com/avatar/{{ $emailHash }}?d=identicon&s=50" class="avatar-image">
                        <span>{{ Soda::auth()->user()->username }}</span>
                    </a>
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
                            <span>@lang('soda::misc.view_site')</span>
                        </a>
                    </li>
                    <li>
                        <a class='dropdown-item' href="#" data-toggle="modal" data-target="#newQuicklinkModal">
                            <i class="mdi mdi-share"></i>
                            <span>@lang('soda::misc.quicklink_this_page')</span>
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
                            <span>@lang('soda::actions.logout')</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
