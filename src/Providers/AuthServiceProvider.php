<?php

namespace Soda\Cms\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    //bleh
    public function register() {
    }

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot(GateContract $gate) {

        //Auth::extend('soda', function($app, array $config) {
        //    // Return an instance of Illuminate\Contracts\Auth\UserProvider...
        //    return new SodaGuard($app['soda.connection']);
        //});
        //
        //Auth::extend('soda', function($app, $name, array $config) {
        //    // Return an instance of Illuminate\Contracts\Auth\Guard...
        //    dd('extender');
        //    //return new UsernameGuard($config['provider'],
        //    //    Auth::createUserProvider($config['provider']),
        //    //    $this->app['session.store'],
        //    //    $this->app['request']
        //    //    );
        //});
        //
        $this->registerPolicies($gate);
        //

        //TODO: move these to database to define all permisssions?
        //TODO: and probably into a permissions object of some sort too.
        $gate->define('soda.dashboard', function ($user) {
            if (isset($user->role)) {
                if ($user->role->id == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
    }

}
