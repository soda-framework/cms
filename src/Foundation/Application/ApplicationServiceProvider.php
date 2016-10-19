<?php

namespace Soda\Cms\Foundation\Application;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Application\Interfaces\CachedApplicationRepositoryInterface;
use Soda\Cms\Foundation\Application\Models\Application;
use Soda\Cms\Foundation\Application\Models\ApplicationUrl;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Foundation\Application\Repositories\ApplicationRepository;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Foundation\Application\Repositories\CachedApplicationRepository;

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
