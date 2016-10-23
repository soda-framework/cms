<?php
namespace Soda\Cms\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
                'icon'        => 'fa fa-home',
                'label'       => 'Dashboard',
                'isCurrent'   => soda_request_is() || soda_request_is('/'),
                'permissions' => 'access-cms',
            ]);

            $menu->addItem('Application', [
                'url'         => route('soda.application.edit'),
                'icon'        => 'fa fa-database',
                'label'       => 'Application',
                'isCurrent'   => soda_request_is('application*'),
                'permissions' => 'view-application-settings',
            ]);

            $menu->addItem('Pages', [
                'url'         => route('soda.pages.index'),
                'icon'        => 'fa fa-file-text-o',
                'label'       => 'Pages',
                'isCurrent'   => soda_request_is('pages*'),
                'permissions' => 'view-pages',
            ]);

            $menu->addItem('Content Types', [
                'label'     => 'Scaffolding',
                'icon'      => 'fa fa-object-group',
                'isCurrent' => soda_request_is('page-types*') || soda_request_is('block-types*') || soda_request_is('blocks*') || soda_request_is('fields*'),
            ]);

            $menu['Content Types']->addChild('Page Types', [
                'url'         => route('soda.page-types.index'),
                'label'       => 'Page Types',
                'isCurrent'   => soda_request_is('page-types*'),
                'permissions' => 'manage-page-types',
            ]);

            $menu['Content Types']->addChild('Block Types', [
                'url'         => route('soda.block-types.index'),
                'label'       => 'Block Types',
                'isCurrent'   => soda_request_is('block-types*'),
                'permissions' => 'manage-block-types',
            ]);

            $menu['Content Types']->addChild('Fields', [
                'url'         => route('soda.fields.index'),
                'label'       => 'Fields',
                'isCurrent'   => soda_request_is('fields*'),
                'permissions' => 'manage-fields',
            ]);

            $menu->addItem('User Management', [
                'label'       => 'User Management',
                'icon'        => 'fa fa-users',
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

            $menu->addItem('Applications', [
                'url'         => '#',
                'label'       => 'Applications',
                'icon'        => 'fa fa-desktop',
                'isCurrent'   => soda_request_is('applications*'),
                'permissions' => 'manage-applications',
            ]);

            $menu->addItem('Application Settings', [
                'url'         => '#',
                'label'       => 'Application Settings',
                'icon'        => 'fa fa-cog',
                'isCurrent'   => soda_request_is('application-settings*'),
                'permissions' => 'manage-applications',
            ]);

            return $menu;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'soda.menu.registrar',
            'soda.menu',
        ];
    }
}
