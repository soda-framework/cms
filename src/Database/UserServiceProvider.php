<?php

namespace Soda\Cms\Database;

use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\User;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Models\Permission;
use Soda\Cms\Database\Repositories\RoleRepository;
use Soda\Cms\Database\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Database\Repositories\PermissionRepository;
use Soda\Cms\Database\Repositories\Contracts\RoleRepositoryInterface;
use Soda\Cms\Database\Repositories\Contracts\UserRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Repositories\Contracts\PermissionRepositoryInterface;

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
        'soda.user.repository'       => [UserRepositoryInterface::class, UserRepository::class],
        'soda.role.repository'       => [RoleRepositoryInterface::class, RoleRepository::class],
        'soda.permission.repository' => [PermissionRepositoryInterface::class, PermissionRepository::class],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'SodaUser' => User::class,
        ]);

        $this->app->config->set('laratrust.role', Role::class);
        $this->app->config->set('laratrust.permission', Permission::class);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('soda.permission.repository', function ($app) {
            return new PermissionRepository(new Permission);
        });

        $this->app->bind('soda.role.repository', function ($app) {
            return new RoleRepository(new Role);
        });

        $this->app->bind('soda.user.repository', function ($app) {
            return new UserRepository(new User);
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
