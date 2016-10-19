<?php

namespace Soda\Cms\Database\Users;

use Illuminate\Support\ServiceProvider;
use Laratrust\LaratrustFacade;
use Laratrust\LaratrustServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Foundation\Providers\Traits\RegistersFacadesAndDependencies;
use Soda\Cms\Database\User\Interfaces\PermissionInterface;
use Soda\Cms\Database\User\Interfaces\RoleInterface;
use Soda\Cms\Database\User\Interfaces\UserInterface;
use Soda\Cms\Database\User\Models\Permission;
use Soda\Cms\Database\User\Models\Role;
use Soda\Cms\Database\User\Models\User;

class UserServiceProvider extends ServiceProvider
{
    use RegistersFacadesAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'SodaUser' => resolve_class(UserInterface::class),
        ]);

        $this->app->config->set('laratrust.role', resolve_class(RoleInterface::class));
        $this->app->config->set('laratrust.permission', resolve_class(PermissionInterface::class));
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

        $this->app->bind(UserInterface::class, User::class);
        $this->app->bind(RoleInterface::class, Role::class);
        $this->app->bind(PermissionInterface::class, Permission::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            UserInterface::class,
            RoleInterface::class,
            PermissionInterface::class,
        ];
    }
}
