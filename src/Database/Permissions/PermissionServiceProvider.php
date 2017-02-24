<?php

namespace Soda\Cms\Database\Permissions;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Permissions\Models\Permission;
use Soda\Cms\Database\Permissions\Interfaces\PermissionInterface;
use Soda\Cms\Database\Permissions\Repositories\PermissionRepository;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Permissions\Interfaces\PermissionRepositoryInterface;

class PermissionServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.permission.model'      => [PermissionInterface::class, Permission::class],
        'soda.permission.repository' => [PermissionRepositoryInterface::class, PermissionRepository::class],
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
        $this->app->bind('soda.permission.model', function($app) {
            return new Permission;
        });

        $this->app->bind('soda.permission.repository', function($app) {
            return new PermissionRepository($app['soda.permission.model']);
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
