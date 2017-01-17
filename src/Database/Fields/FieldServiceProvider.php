<?php

namespace Soda\Cms\Database\Fields;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Fields\Models\Field;
use Soda\Cms\Database\Fields\Models\Media;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Fields\Interfaces\MediaInterface;
use Soda\Cms\Database\Fields\Repositories\FieldRepository;
use Soda\Cms\Database\Fields\Interfaces\FieldRepositoryInterface;
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
        'soda.field.model'      => [FieldInterface::class, Field::class],
        'soda.media.model'      => [MediaInterface::class, Media::class],
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
        $this->app->bind('soda.field.model', function ($app) {
            return new Field;
        });

        $this->app->bind('soda.media.model', function ($app) {
            return new Media;
        });

        $this->app->bind('soda.field.repository', function ($app) {
            return new FieldRepository($app['soda.field.model']);
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
