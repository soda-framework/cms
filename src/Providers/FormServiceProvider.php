<?php
namespace Soda\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Components\Forms\FormBuilder;
use Soda\Cms\Components\Forms\FormFieldRegistrar;
use Soda\Cms\Support\Facades\SodaFormFacade;
use Soda\Cms\Support\Traits\SodaServiceProviderTrait;

class FormServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->registerFacades([
            'SodaForm' => SodaFormFacade::class,
        ]);

        $this->app->bind('soda.form.registrar', function ($app) {
            return new FormFieldRegistrar($app['config']->get('soda.fields'));
        });

        $this->app->bind('soda.form', function ($app) {
            return new FormBuilder($app['soda.form.registrar']);
        });
    }
}
