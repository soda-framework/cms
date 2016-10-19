<?php
namespace Soda\Cms\Foundation\Support\Providers;

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
        $this->app->config->set('auth.providers.soda', $this->app->config->get('soda.auth.provider'));
        $this->app->config->set('auth.guards.soda', $this->app->config->get('soda.auth.guard'));
        $this->app->config->set('auth.passwords.soda', $this->app->config->get('soda.auth.password'));
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../../../config/auth.php', 'soda.auth');
    }
}
