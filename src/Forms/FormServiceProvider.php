<?php
namespace Soda\Cms\Forms;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Support\Traits\SodaServiceProviderTrait;

class FormServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;
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
        $this->mergeConfigFrom(__DIR__.'/fields.php', 'soda.fields');


        $this->app->singleton('soda.form.registrar', function ($app) {
            return new FormFieldRegistrar($app['config']->get('soda.fields'));
        });

        $this->app->singleton('soda.form', function ($app) {
            return new FormBuilder($app['soda.form.registrar']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'soda.form.registrar',
            'soda.form',
        ];
    }
}
