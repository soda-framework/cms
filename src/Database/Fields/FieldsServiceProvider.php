<?php

namespace Soda\Cms\Database\Fields;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Fields\Interfaces\MediaInterface;
use Soda\Cms\Database\Fields\Models\Field;
use Soda\Cms\Database\Fields\Models\Media;

class FieldsServiceProvider extends ServiceProvider
{
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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FieldInterface::class, Field::class);
        $this->app->bind(MediaInterface::class, Media::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            FieldInterface::class,
            MediaInterface::class,
        ];
    }
}
