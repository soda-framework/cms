<?php
namespace Themes\SodaExample\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Providers\SodaServiceProviderTrait;
use Themes\SodaExample\Handlers\ExceptionHandler;

class SodaExampleThemeServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $defer = false;
    protected $handlesErrors = false;

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'soda-example');
    }

    public function register()
    {
        $this->publishes([__DIR__ . '/../../public' => public_path('themes/soda-example')], 'public');

        $this->registerDependencies([
            RouteServiceProvider::class,
        ]);

        if ($this->handlesErrors) {
            $this->bindErrorHandler();
        }
    }

    public function bindErrorHandler() {
        $this->app->singleton(BaseExceptionHandler::class, ExceptionHandler::class);

        return $this;
    }
}
