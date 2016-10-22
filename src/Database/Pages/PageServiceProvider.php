<?php

namespace Soda\Cms\Database\Pages;

use Franzose\ClosureTable\ClosureTableServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Database\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Pages\Models\DynamicPage;
use Soda\Cms\Database\Pages\Models\Page;
use Soda\Cms\Database\Pages\Models\PageType;
use Soda\Cms\Database\Pages\Repositories\CachedPageRepository;
use Soda\Cms\Database\Pages\Repositories\PageRepository;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

class PageServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.page.model'             => [PageInterface::class, Page::class],
        'soda.page-type.model'        => [PageTypeInterface::class, PageType::class],
        'soda.dynamic-page.model'     => [DynamicPageInterface::class, DynamicPage::class],
        'soda.page.repository'        => [PageRepositoryInterface::class, PageRepository::class],
        'soda.page.cached-repository' => [CachedPageRepositoryInterface::class, CachedPageRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'PageType' => resolve_class('soda.page-type.model'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDependencies([
            ClosureTableServiceProvider::class,
        ]);

        $this->app->bind('soda.page.model', function ($app) {
            return new Page;
        });

        $this->app->bind('soda.page-type.model', function ($app) {
            return new PageType;
        });

        $this->app->bind('soda.dynamic-page.model', function ($app) {
            return new DynamicPage;
        });

        $this->app->singleton('soda.page.repository', function ($app) {
            return new PageRepository($app['soda.page.model']);
        });

        $this->app->singleton('soda.page.cached-repository', function ($app) {
            return new CachedPageRepository($app['soda.page.repository']);
        });

        $this->registerAliases($this->aliases);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->aliases);
    }
}
