<?php

namespace Soda\Cms\Database\Users;

use Illuminate\Support\ServiceProvider;
use Laratrust\LaratrustFacade;
use Laratrust\LaratrustServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindings;
use Soda\Cms\Foundation\Providers\Traits\RegistersFacadesAndDependencies;
use Soda\Cms\Database\User\Interfaces\PermissionInterface;
use Soda\Cms\Database\User\Interfaces\RoleInterface;
use Soda\Cms\Database\User\Interfaces\UserInterface;
use Soda\Cms\Database\User\Models\Permission;
use Soda\Cms\Database\User\Models\Role;
use Soda\Cms\Database\User\Models\User;

class UserServiceProvider extends ServiceProvider
{
    use RegistersFacadesAndDependencies, RegistersBindings;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $bindings = [
        'soda.user.model' => [
            'abstract' => UserInterface::class,
            'concrete' => User::class,
        ],
        'soda.role.model' => [
            'abstract' => RoleInterface::class,
            'concrete' => Role::class,
        ],
        'soda.permission.model' => [
            'abstract' => PermissionInterface::class,
            'concrete' => Permission::class,
        ],
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
        $this->registerBindings($this->bindings);

        $this->registerDependencies([
            LaratrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Laratrust' => LaratrustFacade::class,
        ]);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
