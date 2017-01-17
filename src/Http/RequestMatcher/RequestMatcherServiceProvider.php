<?php

namespace Soda\Cms\Http\RequestMatcher;

use Illuminate\Support\ServiceProvider;

class RequestMatcherServiceProvider extends ServiceProvider
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
        $this->app->singleton('soda.request-matcher', function ($app) {
            return new RequestMatcher($app['soda.application.cached-repository']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'soda.request-matcher',
        ];
    }
}
