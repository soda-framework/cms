<?php

namespace Soda\Cms\Database;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\Application;
use Soda\Cms\Database\Models\ApplicationUrl;
use Soda\Cms\Database\Repositories\ApplicationRepository;
use Soda\Cms\Database\Repositories\CachedApplicationRepository;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\ApplicationRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\CachedApplicationRepositoryInterface;

class ApplicationServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.application.repository'        => [ApplicationRepositoryInterface::class, ApplicationRepository::class],
        'soda.application.cached-repository' => [CachedApplicationRepositoryInterface::class, CachedApplicationRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('soda.application.repository', function ($app) {
            return new ApplicationRepository(new Application, new ApplicationUrl);
        });

        $this->app->singleton('soda.application.cached-repository', function ($app) {
            return new CachedApplicationRepository($app['soda.application.repository']);
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
