<?php
namespace Soda\Cms\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Knp\Menu\Matcher\Matcher;
use Request;
use Soda\Cms\Components\Menu\MenuBuilder;
use Soda\Cms\Components\Menu\MenuFactory;
use Soda\Cms\Components\Menu\MenuRegistrar;
use Soda\Cms\Components\Menu\MenuRenderer;
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
        $this->app['events']->listen('Illuminate\Routing\Events\RouteMatched', function() {
            $this->buildCmsSidebar();
        });
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

        $this->app->bind('soda.menu.factory', function ($app) {
            return new MenuFactory;
        });

        $this->app->bind('soda.menu', function ($app) {
            return new MenuBuilder($app['soda.menu.registrar'], $app['soda.menu.factory']);
        });
    }

    protected function buildCmsSidebar() {
        $menu = SodaMenu::menu('sidebar', [
            'childrenAttributes' => [
                'class' => 'nav nav-sidebar',
            ],
        ]);

        $menu->setRenderer(new MenuRenderer(new Matcher));

        $menu->addChild('Dashboard', [
            'uri'     => route('soda.home'),
            'label'   => 'Dashboard',
            'current' => Request::is('cms') || Request::is('cms/'),
            'extras'  => [
                'icon'        => 'fa fa-home',
                'permissions' => 'access-cms',
            ],
        ]);

        $menu->addChild('Content', [
            'label'   => 'Content',
            'current' => Request::is('cms/pages*') || Request::is('cms/blocks*'),
            'extras'  => [
                'icon' => 'fa fa-file-o',
            ],
        ]);

        $menu['Content']->addChild('Pages', [
            'uri'     => route('soda.page'),
            'label'   => 'Pages',
            'current' => Request::is('cms/pages*'),
            'extras'  => [
                'permissions' => 'view-pages',
            ]
        ]);

        $menu['Content']->addChild('Blocks', [
            'uri'     => route('soda.block'),
            'label'   => 'Blocks',
            'current' => Request::is('cms/blocks*'),
            'extras'  => [
                'permissions' => 'view-blocks',
            ]
        ]);

        $menu->addChild('Content Types', [
            'label'   => 'Content Types',
            'current' => Request::is('cms/page-types*') || Request::is('cms/block-types*') || Request::is('cms/fields*'),
            'extras'  => [
                'icon' => 'fa fa-pencil-square-o',
            ],
        ]);

        $menu['Content Types']->addChild('Page Types', [
            'uri'     => route('soda.page_type'),
            'label'   => 'Page Types',
            'current' => Request::is('cms/page-types*'),
            'extras'  => [
                'permissions' => 'manage-page-types',
            ]
        ]);

        $menu['Content Types']->addChild('Block Types', [
            'uri'     => route('soda.block_type'),
            'label'   => 'Block Types',
            'current' => Request::is('cms/block-types*'),
            'extras'  => [
                'permissions' => 'manage-block-types',
            ]
        ]);

        $menu['Content Types']->addChild('Fields', [
            'uri'     => route('soda.field'),
            'label'   => 'Fields',
            'current' => Request::is('cms/fields*'),
            'extras'  => [
                'permissions' => 'manage-fields',
            ]
        ]);

        $menu->addChild('Users', [
            'uri'     => route('soda.user'),
            'label'   => 'Users',
            'current' => Request::is('cms/users*'),
            'extras'  => [
                'icon'        => 'fa fa-users',
                'permissions' => 'view-users',
            ],
        ]);

        $menu->addChild('Applications', [
            'uri'     => '#',
            'label'   => 'Applications',
            'current' => Request::is('cms/applications*'),
            'extras'  => [
                'icon'        => 'fa fa-desktop',
                'permissions' => 'manage-applications',
            ],
        ]);

        $menu->addChild('Application Settings', [
            'uri'     => '#',
            'label'   => 'Application Settings',
            'current' => Request::is('cms/application-settings*'),
            'extras'  => [
                'icon'        => 'fa fa-cog',
                'permissions' => 'manage-applications',
            ],
        ]);

        $menu->addChild('Navigation', [
            'uri'     => route('soda.navigation'),
            'label'   => 'Navigation',
            'current' => Request::is('cms/navigation*'),
            'extras'  => [
                'icon'        => 'fa fa-compass',
                'permissions' => 'view-navigation',
            ],
        ]);
    }
}
