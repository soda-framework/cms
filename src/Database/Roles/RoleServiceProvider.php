<?php

namespace Soda\Cms\Database\Roles;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Roles\Models\Role;
use Soda\Cms\Database\Roles\Interfaces\RoleInterface;
use Soda\Cms\Database\Roles\Repositories\RoleRepository;
use Soda\Cms\Database\Roles\Interfaces\RoleRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

class RoleServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.role.model'      => [RoleInterface::class, Role::class],
        'soda.role.repository' => [RoleRepositoryInterface::class, RoleRepository::class],
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
        $this->app->bind('soda.role.model', function ($app) {
            return new Role;
        });

        $this->app->bind('soda.role.repository', function ($app) {
            return new RoleRepository($app['soda.role.model']);
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
