<?php

namespace Soda\Cms\InterfaceBuilder\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->buildCmsSidebar();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('soda.menu.registrar', function ($app) {
            return new MenuRegistrar;
        });

        $this->app->singleton('soda.menu', function ($app) {
            return new MenuBuilder($app['soda.menu.registrar']);
        });
    }

    protected function buildCmsSidebar()
    {
        $this->app['soda.menu']->menu('sidebar', function ($menu) {
            $menu->setAttributes([
                'class' => 'nav nav-sidebar',
            ]);

            $menu->setRenderer(new SidebarMenuRenderer);

            $menu->addItem('Dashboard', [
                'url'         => route('soda.home'),
                'icon'        => 'mdi mdi-collage',  //mdi-view-dashboard
                'label'       => 'Dashboard',
                'isCurrent'   => soda_request_is() || soda_request_is('/'),
                'permissions' => 'access-cms',
            ]);

            $menu->addItem('Settings', [
                'url'         => route('soda.settings.edit'),
                'icon'        => 'mdi mdi-settings', //mdi-lock-pattern mdi-buffer
                'label'       => 'Settings',
                'isCurrent'   => soda_request_is('settings*'),
                'permissions' => 'view-application-settings',
            ]);

            $menu->addItem('Pages', [
                'url'         => route('soda.content.index'),
                'icon'        => 'mdi mdi-file-outline',
                'label'       => 'Pages',
                'isCurrent'   => soda_request_is('content') || soda_request_is('content/*'),
                'permissions' => 'view-pages',
            ]);

            $menu->addItem('Scaffolding', [
                'label'     => 'Scaffolding',
                'icon'      => 'mdi mdi-layers',
                'isCurrent' => soda_request_is('content-types*') || soda_request_is('block-types*') || soda_request_is('blocks*') || soda_request_is('fields*'),
            ]);

            $menu['Scaffolding']->addChild('Content Types', [
                'url'         => route('soda.content-types.index'),
                'label'       => 'Content Types',
                'isCurrent'   => soda_request_is('content-types*'),
                'permissions' => 'manage-content-types',
            ]);

            $menu['Scaffolding']->addChild('Block Types', [
                'url'         => route('soda.block-types.index'),
                'label'       => 'Block Types',
                'isCurrent'   => soda_request_is('block-types*'),
                'permissions' => 'manage-block-types',
            ]);

            $menu['Scaffolding']->addChild('Fields', [
                'url'         => route('soda.fields.index'),
                'label'       => 'Fields',
                'isCurrent'   => soda_request_is('fields*'),
                'permissions' => 'manage-fields',
            ]);

            $menu->addItem('User Management', [
                'label'       => 'Users',
                'icon'        => 'mdi mdi-contacts',
                'isCurrent'   => soda_request_is('users*') || soda_request_is('roles*') || soda_request_is('permissions*'),
                'permissions' => 'view-users',
            ]);

            $menu['User Management']->addChild('Users', [
                'url'         => route('soda.users.index'),
                'label'       => 'Users',
                'isCurrent'   => soda_request_is('users*'),
                'permissions' => 'view-users',
            ]);

            $menu['User Management']->addChild('Roles', [
                'url'         => route('soda.roles.index'),
                'label'       => 'Roles',
                'isCurrent'   => soda_request_is('roles*'),
                'permissions' => 'view-roles',
            ]);

            $menu['User Management']->addChild('Permissions', [
                'url'         => route('soda.permissions.index'),
                'label'       => 'Permissions',
                'isCurrent'   => soda_request_is('permissions*'),
                'permissions' => 'view-permissions',
            ]);

            return $menu;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'soda.menu.registrar',
            'soda.menu',
        ];
    }
}
