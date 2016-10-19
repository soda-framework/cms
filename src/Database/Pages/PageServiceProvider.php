<?php

namespace Soda\Cms\Database\Pages;

use Franzose\ClosureTable\ClosureTableServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Pages\Models\Page;
use Soda\Cms\Database\Pages\Models\PageType;
use Soda\Cms\Database\Pages\Repositories\CachedPageRepository;
use Soda\Cms\Database\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Database\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Database\Pages\Repositories\PageRepository;
use Soda\Cms\Database\Pages\Models\DynamicPage;
use Soda\Cms\Foundation\Providers\Traits\RegistersFacadesAndDependencies;

class PageServiceProvider extends ServiceProvider
{
    use RegistersFacadesAndDependencies;
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
        Relation::morphMap([
            'PageType' => resolve_class(PageTypeInterface::class),
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

        $this->app->bind(PageInterface::class, Page::class);
        $this->app->bind(PageTypeInterface::class, PageType::class);
        $this->app->bind(DynamicPageInterface::class, DynamicPage::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(CachedPageRepositoryInterface::class, CachedPageRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PageInterface::class,
            PageTypeInterface::class,
            DynamicPageInterface::class,
            PageRepositoryInterface::class,
            CachedPageRepositoryInterface::class,
        ];
    }
}
