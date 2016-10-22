<?php

namespace Soda\Cms\Database\Users;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laratrust\LaratrustFacade;
use Laratrust\LaratrustServiceProvider;
use Soda\Cms\Database\Users\Interfaces\PermissionInterface;
use Soda\Cms\Database\Users\Interfaces\RoleInterface;
use Soda\Cms\Database\Users\Interfaces\UserInterface;
use Soda\Cms\Database\Users\Models\Permission;
use Soda\Cms\Database\Users\Models\Role;
use Soda\Cms\Database\Users\Models\User;
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
        'soda.user.model'       => [UserInterface::class, User::class],
        'soda.role.model'       => [RoleInterface::class, Role::class],
        'soda.permission.model' => [PermissionInterface::class, Permission::class],
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
        $this->registerDependencies([
            LaratrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Laratrust' => LaratrustFacade::class,
        ]);

        $this->app->bind('soda.user.model', function ($app) {
            return new User;
        });

        $this->app->bind('soda.role.model', function ($app) {
            return new Role;
        });

        $this->app->bind('soda.permission.model', function ($app) {
            return new Permission;
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
