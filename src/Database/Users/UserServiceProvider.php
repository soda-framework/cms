<?php

namespace Soda\Cms\Database\Users;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Database\Users\Interfaces\UserInterface;
use Soda\Cms\Database\Users\Repositories\UserRepository;
use Soda\Cms\Database\Users\Interfaces\UserRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

class UserServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.user.model'      => [UserInterface::class, User::class],
        'soda.user.repository' => [UserRepositoryInterface::class, UserRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'SodaUser' => resolve_class('soda.user.model'),
        ]);

        $this->app->config->set('laratrust.role', resolve_class('soda.role.model'));
        $this->app->config->set('laratrust.permission', resolve_class('soda.permission.model'));
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('soda.user.model', function ($app) {
            return new User;
        });

        $this->app->rebinding('soda.user.model', function ($app, $userModel) {
            Relation::morphMap([
                'SodaUser' => get_class($userModel),
            ]);
        });

        $this->app->bind('soda.user.repository', function ($app) {
            return new UserRepository($app['soda.user.model']);
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
