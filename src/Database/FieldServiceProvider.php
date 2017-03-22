<?php

namespace Soda\Cms\Database;

use Soda\Cms\Database\Models\Field;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Repositories\FieldRepository;
use Soda\Cms\Database\Repositories\Contracts\FieldRepositoryInterface;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

class FieldServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $aliases = [
        'soda.field.repository' => [FieldRepositoryInterface::class, FieldRepository::class],
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
        $this->app->bind('soda.field.repository', function ($app) {
            return new FieldRepository(new Field);
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
