<?php

namespace Soda\Cms\Database\Application;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Models\Application;
use Soda\Cms\Database\Application\Models\ApplicationUrl;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Database\Application\Repositories\ApplicationRepository;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Repositories\CachedApplicationRepository;

class ApplicationServiceProvider extends ServiceProvider
{
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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApplicationInterface::class, Application::class);
        $this->app->bind(ApplicationUrlInterface::class, ApplicationUrl::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(CachedApplicationRepositoryInterface::class, CachedApplicationRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ApplicationInterface::class,
            ApplicationTypeInterface::class,
            ApplicationRepositoryInterface::class,
            CachedApplicationRepositoryInterface::class,
        ];
    }
}
