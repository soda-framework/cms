<?php

namespace Themes\SodaExample\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
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
        $this->app->config->set('auth.providers.soda-example', $this->app->config->get('themes.soda-example.auth.provider'));
        $this->app->config->set('auth.guards.soda-example', $this->app->config->get('themes.soda-example.auth.guard'));
        $this->app->config->set('auth.passwords.soda-example', $this->app->config->get('themes.soda-example.auth.password'));
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
    }
}
