<?php

namespace Soda\Cms\Providers;

use Soda\Cms\Models\Page;
use Soda\Cms\Models\Role;
use Soda\Cms\Models\User;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\PageType;
use Laratrust\LaratrustFacade;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Permission;
use Illuminate\Support\ServiceProvider;
use Laratrust\LaratrustServiceProvider;
use Soda\Cms\Models\Observers\PageObserver;
use Soda\Cms\Models\Observers\BlockObserver;
use Soda\Cms\Models\Observers\DynamicObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Support\Traits\SodaServiceProviderTrait;
use Franzose\ClosureTable\ClosureTableServiceProvider;

class EloquentServiceProvider extends ServiceProvider
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
        $this->configure();
        $this->bootObservers();

        Relation::morphMap([
            'PageType'  => PageType::class,
            'BlockType' => BlockType::class,
            'SodaUser'  => User::class,
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
            LaratrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Laratrust' => LaratrustFacade::class,
        ]);
    }

    protected function bootObservers()
    {
        Block::observe(BlockObserver::class);
        Page::observe(PageObserver::class);
        BlockType::observe(DynamicObserver::class);
        PageType::observe(DynamicObserver::class);
    }

    protected function configure()
    {
        $this->app->config->set('laratrust.role', Role::class);
        $this->app->config->set('laratrust.permission', Permission::class);
    }
}
