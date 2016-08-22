<?php namespace Themes\SodaExample\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Themes\SodaExample\Guards\UsernameGuard;
use Themes\SodaExample\UserProviders\UsernameUserProvider;

class AuthServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        Auth::extend('username', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new UsernameGuard($config['provider'],
                Auth::createUserProvider($config['provider']),
                $this->app['session.store'],
                $this->app['request']
            );
        });


        Auth::provider('username', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new UsernameUserProvider($app['hash'], $config['model']);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
