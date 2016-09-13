<?php
namespace Soda\Cms\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Request;
use Soda\Cms\Components\Menu\MenuBuilder;
use Soda\Cms\Components\Menu\MenuFactory;
use Soda\Cms\Components\Menu\MenuRegistrar;
use Soda\Cms\Components\Menu\SidebarMenuRenderer;
use Soda\Cms\Facades\SodaMenuFacade;
use SodaMenu;

class MenuServiceProvider extends ServiceProvider {
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
    public function boot() {
        $this->buildCmsSidebar();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
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

    protected function buildCmsSidebar() {
        SodaMenu::menu('sidebar', function($menu) {
            $menu->setAttributes([
                'class' => 'nav nav-sidebar',
            ]);

            $menu->setRenderer(new SidebarMenuRenderer);

            $menu->addItem('Dashboard', [
                'url'         => route('soda.home'),
                'icon'        => 'fa fa-home',
                'label'       => 'Dashboard',
                'isCurrent'   => Request::is('cms') || Request::is('cms/'),
                'permissions' => 'access-cms',
            ]);

            $menu->addItem('Content', [
                'label'     => 'Content',
                'icon'      => 'fa fa-file-o',
                'isCurrent' => Request::is('cms/pages*') || Request::is('cms/blocks*'),
            ]);

            $menu['Content']->addChild('Pages', [
                'url'         => route('soda.page'),
                'label'       => 'Pages',
                'isCurrent'   => Request::is('cms/pages*'),
                'permissions' => 'view-pages',
            ]);

            $menu['Content']->addChild('Blocks', [
                'url'         => route('soda.block'),
                'label'       => 'Blocks',
                'isCurrent'   => Request::is('cms/blocks*'),
                'permissions' => 'view-blocks',
            ]);

            $menu->addItem('Content Types', [
                'label'     => 'Content Types',
                'icon'      => 'fa fa-pencil-square-o',
                'isCurrent' => Request::is('cms/page-types*') || Request::is('cms/block-types*') || Request::is('cms/fields*'),
            ]);

            $menu['Content Types']->addChild('Page Types', [
                'url'         => route('soda.page_type'),
                'label'       => 'Page Types',
                'isCurrent'   => Request::is('cms/page-types*'),
                'permissions' => 'manage-page-types',
            ]);

            $menu['Content Types']->addChild('Block Types', [
                'url'         => route('soda.block_type'),
                'label'       => 'Block Types',
                'isCurrent'   => Request::is('cms/block-types*'),
                'permissions' => 'manage-block-types',
            ]);

            $menu['Content Types']->addChild('Fields', [
                'url'         => route('soda.field'),
                'label'       => 'Fields',
                'isCurrent'   => Request::is('cms/fields*'),
                'permissions' => 'manage-fields',
            ]);

            $menu->addItem('Users', [
                'url'         => route('soda.user'),
                'label'       => 'Users',
                'icon'        => 'fa fa-users',
                'isCurrent'   => Request::is('cms/users*'),
                'permissions' => 'view-users',
            ]);

            $menu->addItem('Applications', [
                'url'         => '#',
                'label'       => 'Applications',
                'icon'        => 'fa fa-desktop',
                'isCurrent'   => Request::is('cms/applications*'),
                'permissions' => 'manage-applications',
            ]);

            $menu->addItem('Application Settings', [
                'url'         => '#',
                'label'       => 'Application Settings',
                'icon'        => 'fa fa-cog',
                'isCurrent'   => Request::is('cms/application-settings*'),
                'permissions' => 'manage-applications',
            ]);

            $menu->addItem('Navigation', [
                'url'         => route('soda.navigation'),
                'label'       => 'Navigation',
                'icon'        => 'fa fa-compass',
                'isCurrent'   => Request::is('cms/navigation*'),
                'permissions' => 'view-navigation',
            ]);

            return $menu;
        });
    }
}
