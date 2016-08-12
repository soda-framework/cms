
<div class="col-sm-2 sidebar">
    <ul class="nav nav-sidebar">
        @if(config("soda.menu.pages", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/pages*')?'active':''}}"  href="{{route('soda.page')}}">
                    <i class="fa fa-file-o"></i> <span>Pages</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.page-types", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/page-types*')?'active':''}}" href="{{route('soda.page_type')}}">
                    <i class="fa fa-edit"></i> <span>Page Types</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.blocks", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/blocks*')?'active':''}}" href="{{route('soda.block')}}">
                    <i class="fa fa-square"></i> <span>Blocks</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.block-types", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/block-types*')?'active':''}}" href="{{route('soda.block_type')}}">
                    <i class="fa fa-pencil-square"></i> <span>Block Types</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.fields", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/field*')?'active':''}}" href="{{route('soda.field')}}">
                    <i class="fa fa-pencil"></i> <span>Fields</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.users", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/user*')?'active':''}}" href="{{route('soda.user')}}">
                    <i class="fa fa-users"></i> <span>Users</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.applications", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/applications*')?'active':''}}" href="#"><i class="fa fa-desktop"></i> Applications</a>
            </li>
        @endif

        @if(config("soda.menu.application-settings", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/settings*')?'active':''}}" href="#">
                    <i class="fa fa-cog"></i> <span>Application Settings</span>
                </a>
            </li>
        @endif

        @if(config("soda.menu.navigation", true))
            <li class="nav-item">
                <a class="nav-link {{Request::is('cms/navigation*')?'active':''}}" href="{{route('soda.navigation')}}">
                    <i class="fa fa-compass"></i> <span>Navigation</span>
                </a>
            </li>
        @endif

        @foreach( event(new Soda\Events\NavigationWasRendered()) as $item)
            {!! $item !!}
        @endforeach
    </ul>
</div>
