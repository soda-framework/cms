<?php

namespace Soda\Cms\Database\Fields;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Fields\Interfaces\MediaInterface;
use Soda\Cms\Database\Fields\Models\Field;
use Soda\Cms\Database\Fields\Models\Media;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindings;

class FieldsServiceProvider extends ServiceProvider
{
    use RegistersBindings;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    protected $bindings = [
        'soda.field.model' => [
            'abstract' => FieldInterface::class,
            'concrete' => Field::class,
        ],
        'soda.media.model' => [
            'abstract' => MediaInterface::class,
            'concrete' => Media::class,
        ],
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
        $this->registerBindings($this->bindings);
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
