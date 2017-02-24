<?php

namespace Soda\Cms\Database\Application;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Application\Models\Application;
use Soda\Cms\Database\Application\Models\ApplicationUrl;
use Soda\Cms\Database\Application\Models\ApplicationSetting;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;
use Soda\Cms\Database\Application\Repositories\ApplicationRepository;
use Soda\Cms\Database\Application\Interfaces\ApplicationSettingInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Application\Repositories\CachedApplicationRepository;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;
use Soda\Cms\Database\Application\Interfaces\CachedApplicationRepositoryInterface;

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
        'soda.application.model'             => [ApplicationInterface::class, Application::class],
        'soda.application-url.model'         => [ApplicationUrlInterface::class, ApplicationUrl::class],
        'soda.application-setting.model'     => [ApplicationSettingInterface::class, ApplicationSetting::class],
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
        $this->app->bind('soda.application.model', function ($app) {
            return new Application;
        });

        $this->app->bind('soda.application-url.model', function ($app) {
            return new ApplicationUrl;
        });

        $this->app->bind('soda.application-setting.model', function ($app) {
            return new ApplicationSetting;
        });

        $this->app->singleton('soda.application.repository', function ($app) {
            return new ApplicationRepository($app['soda.application.model'], $app['soda.application-url.model']);
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
