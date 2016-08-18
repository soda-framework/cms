<?php namespace Themes\SodaTheme\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Themes\SodaTheme\Guards\UsernameGuard;
use Themes\SodaTheme\UserProviders\UsernameUserProvider;

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
