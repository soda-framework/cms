<?php
namespace Themes\SodaExample\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;
use Illuminate\Support\Traits\ServiceProvider;
use Soda\Cms\Support\ThemeExceptionHandler;
use Soda\Cms\Providers\SodaServiceProviderTrait;

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
        $this->mergeConfigFrom(__DIR__.'/../../config/auth.php', 'themes.soda-example.auth');

        $this->registerDependencies([
            RouteServiceProvider::class,
            AuthServiceProvider::class,
        ]);

        if ($this->handlesErrors) {
            $this->bindErrorHandler();
        }
    }

    public function bindErrorHandler() {
        $this->app->singleton(BaseExceptionHandler::class, function ($app) {
            return (new ThemeExceptionHandler())->setTheme('soda-example');
        });

        return $this;
    }
}
