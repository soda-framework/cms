<?php

$menu = [
    [
            'uri'        => route('soda.home'),
            'icon'       => 'fa fa-home',
            'label'      => 'Dashboard',
            'current'    => Request::is('cms') || Request::is('cms/'),
            'display'    => Auth::user()->can('access-cms'),
    ],
    [
        'id'         => 'content',
        'icon'       => 'fa fa-file-o',
        'label'      => 'Content',
        'current'    => Request::is('cms/pages*') || Request::is('cms/blocks*'),
        'children'   => [
            [
                'uri'        => route('soda.page'),
                'label'      => 'Pages',
                'current'    => Request::is('cms/pages*'),
                'display'    => Auth::user()->can('view-pages'),
            ],
            [
                'uri'        => route('soda.block'),
                'label'      => 'Blocks',
                'current'    => Request::is('cms/blocks*'),
                'display'    => Auth::user()->can('view-blocks'),
            ],
        ]
    ],
    [
        'id'         => 'content-types',
        'icon'       => 'fa fa-pencil-square-o',
        'label'      => 'Content Types',
        'current'    => Request::is('cms/page-types*') || Request::is('cms/block-types*') || Request::is('cms/fields*'),
        'children'   => [
            [
                'uri'        => route('soda.page_type'),
                'label'      => 'Page Types',
                'current'    => Request::is('cms/page-types*'),
                'display'    => Auth::user()->can('manage-page-types'),
            ],
            [
                'uri'        => route('soda.block_type'),
                'label'      => 'Block Types',
                'current'    => Request::is('cms/block-types*'),
                'display'    => Auth::user()->can('manage-block-types'),
            ],
            [
                'uri'        => route('soda.field'),
                'label'      => 'Fields',
                'current'    => Request::is('cms/fields*'),
                'display'    => Auth::user()->can('manage-fields'),
            ],
        ]
    ],
    [
        'uri'        => route('soda.user'),
        'icon'       => 'fa fa-users',
        'label'      => 'Users',
        'current'    => Request::is('cms/users*'),
        'display'    => Auth::user()->can('view-users'),
    ],
    [
        'uri'        => '#',
        'icon'       => 'fa fa-desktop',
        'label'      => 'Applications',
        'current'    => Request::is('cms/applications*'),
        'display'    => Auth::user()->can('manage-applications'),
    ],
    [
        'uri'        => '#',
        'icon'       => 'fa fa-cog',
        'label'      => 'Application Settings',
        'current'    => Request::is('cms/application-settings*'),
        'display'    => Auth::user()->can('manage-applications'),
    ],
    [
        'uri'        => route('soda.navigation'),
        'icon'       => 'fa fa-compass',
        'label'      => 'Navigation',
        'current'    => Request::is('cms/navigation*'),
        'display'    => Auth::user()->can('view-navigation'),
    ],
];

?>

<div class="col-xs-2 sidebar">
    {!! SodaMenu::render('sidebar', ['currentClass' => 'active', 'branch_class' => 'nav-item-group', 'leaf_class' => 'nav-item']) !!}
</div>


<script>
    $(function(){
        $(".nav-item-group.active .collapse").collapse('show');
    });
</script>
