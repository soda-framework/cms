<?php

namespace Soda\Cms\Foundation\Pages;

use Franzose\ClosureTable\ClosureTableServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Foundation\Pages\Models\Page;
use Soda\Cms\Foundation\Pages\Models\PageType;
use Soda\Cms\Foundation\Pages\Repositories\CachedPageRepository;
use Soda\Cms\Foundation\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageRepositoryInterface;
use Soda\Cms\Foundation\Pages\Repositories\PageRepository;
use Soda\Cms\Foundation\Pages\Models\DynamicPage;
use Soda\Cms\Foundation\Support\Providers\Traits\RegistersFacadesAndDependencies;

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
