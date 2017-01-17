<?php

namespace Soda\Cms\Providers;

use SodaMenu;
use Soda\Cms\Facades\SodaMenuFacade;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Components\Menu\MenuBuilder;
use Soda\Cms\Components\Menu\MenuRegistrar;
use Soda\Cms\Components\Menu\SidebarMenuRenderer;

class MenuServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;
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
        $this->registerFacades([
            'SodaMenu' => SodaMenuFacade::class,
        ]);

        $this->app->bind('soda.menu.registrar', function ($app) {
            return new MenuRegistrar;
        });

        $this->app->bind('soda.menu', function ($app) {
            return new MenuBuilder($app['soda.menu.registrar']);
        });
    }

    protected function buildCmsSidebar()
    {
        SodaMenu::menu('sidebar', function ($menu) {
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

            $menu->addItem('Content', [
                'label'     => 'Content',
                'icon'      => 'fa fa-file-o',
                'isCurrent' => soda_request_is('pages*') || soda_request_is('blocks*'),
            ]);

            $menu['Content']->addChild('Pages', [
                'url'         => route('soda.page'),
                'label'       => 'Pages',
                'isCurrent'   => soda_request_is('pages*'),
                'permissions' => 'view-pages',
            ]);

            $menu['Content']->addChild('Blocks', [
                'url'         => route('soda.block'),
                'label'       => 'Blocks',
                'isCurrent'   => soda_request_is('blocks*'),
                'permissions' => 'view-blocks',
            ]);

            $menu->addItem('Content Types', [
                'label'     => 'Content Types',
                'icon'      => 'fa fa-pencil-square-o',
                'isCurrent' => soda_request_is('page-types*') || soda_request_is('block-types*') || soda_request_is('fields*'),
            ]);

            $menu['Content Types']->addChild('Page Types', [
                'url'         => route('soda.page_type'),
                'label'       => 'Page Types',
                'isCurrent'   => soda_request_is('page-types*'),
                'permissions' => 'manage-page-types',
            ]);

            $menu['Content Types']->addChild('Block Types', [
                'url'         => route('soda.block_type'),
                'label'       => 'Block Types',
                'isCurrent'   => soda_request_is('block-types*'),
                'permissions' => 'manage-block-types',
            ]);

            $menu['Content Types']->addChild('Fields', [
                'url'         => route('soda.field'),
                'label'       => 'Fields',
                'isCurrent'   => soda_request_is('fields*'),
                'permissions' => 'manage-fields',
            ]);

            $menu->addItem('Users', [
                'url'         => route('soda.user'),
                'label'       => 'Users',
                'icon'        => 'fa fa-users',
                'isCurrent'   => soda_request_is('users*'),
                'permissions' => 'view-users',
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

            $menu->addItem('Navigation', [
                'url'         => route('soda.navigation'),
                'label'       => 'Navigation',
                'icon'        => 'fa fa-compass',
                'isCurrent'   => soda_request_is('navigation*'),
                'permissions' => 'view-navigation',
            ]);

            return $menu;
        });
    }
}
