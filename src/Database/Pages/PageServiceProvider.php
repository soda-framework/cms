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
use Soda\Cms\Foundation\Providers\Traits\RegistersBindings;
use Soda\Cms\Foundation\Providers\Traits\RegistersFacadesAndDependencies;

class PageServiceProvider extends ServiceProvider
{
    use RegistersFacadesAndDependencies, RegistersBindings;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $bindings = [
        'soda.page.model' => [
            'abstract' => PageInterface::class,
            'concrete' => Page::class,
        ],
        'soda.page-type.model' => [
            'abstract' => PageTypeInterface::class,
            'concrete' => PageType::class,
        ],
        'soda.dynamic-page.model' => [
            'abstract' => DynamicPageInterface::class,
            'concrete' => DynamicPage::class,
        ],
        'soda.page.repository' => [
            'instance' => true,
            'abstract' => PageRepositoryInterface::class,
            'concrete' => PageRepository::class,
        ],
        'soda.page.cached-repository' => [
            'instance' => true,
            'abstract' => CachedPageRepositoryInterface::class,
            'concrete' => CachedPageRepository::class,
        ],
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

        $this->registerBindings($this->bindings);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
