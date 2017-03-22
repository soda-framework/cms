<?php

namespace Soda\Cms\Database;

use Soda\Cms\Database\Models\Page;
use Soda\Cms\Database\Models\Field;
use Soda\Cms\Database\Models\PageType;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\BlockType;
use Soda\Cms\Database\Repositories\PageRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Franzose\ClosureTable\ClosureTableServiceProvider;
use Soda\Cms\Database\Repositories\PageTypeRepository;
use Soda\Cms\Database\Repositories\PageBlockRepository;
use Soda\Cms\Database\Repositories\CachedPageRepository;
use Soda\Cms\Database\Repositories\PageTypeBlockRepository;
use Soda\Cms\Database\Repositories\PageTypeFieldRepository;
use Soda\Cms\Database\Repositories\Contracts\PageRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\PageTypeRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\PageBlockRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\CachedPageRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\PageTypeBlockRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\PageTypeFieldRepositoryInterface;

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
        'soda.page.repository'            => [PageRepositoryInterface::class, PageRepository::class],
        'soda.page-type.repository'       => [PageTypeRepositoryInterface::class, PageTypeRepository::class],
        'soda.page-type-block.repository' => [PageTypeBlockRepositoryInterface::class, PageTypeBlockRepository::class],
        'soda.page-type-field.repository' => [PageTypeFieldRepositoryInterface::class, PageTypeFieldRepository::class],
        'soda.page-block.repository'      => [PageBlockRepositoryInterface::class, PageBlockRepository::class],
        'soda.page.cached-repository'     => [CachedPageRepositoryInterface::class, CachedPageRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'PageType' => PageType::class,
        ]);

        app('soda.drafting')->registerDraftables([
            Page::class,
            PageType::class,
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

        $this->app->singleton('soda.page.repository', function ($app) {
            return new PageRepository(new Page);
        });

        $this->app->singleton('soda.page-type.repository', function ($app) {
            return new PageTypeRepository(new PageType);
        });

        $this->app->singleton('soda.page-type-block.repository', function ($app) {
            return new PageTypeBlockRepository(new PageType, new BlockType);
        });

        $this->app->singleton('soda.page-type-field.repository', function ($app) {
            return new PageTypeFieldRepository(new PageType, new Field);
        });

        $this->app->singleton('soda.page-block.repository', function ($app) {
            return new PageBlockRepository(new Page, new BlockType);
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
