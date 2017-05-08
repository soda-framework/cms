<?php

namespace Soda\Cms\Database;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\Application;
use Soda\Cms\Database\Models\ApplicationUrl;
use Soda\Cms\Database\Repositories\ApplicationRepository;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\ApplicationRepositoryInterface;

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
