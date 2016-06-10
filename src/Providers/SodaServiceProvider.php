<?php
namespace Soda\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Soda;
use Soda\Models\Application;

class SodaServiceProvider extends ServiceProvider {
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
    public function boot() {

        $this->buildApp();

        // Loading routes
        //if (!$this->app->routesAreCached()) {
        //	//require __DIR__ . '/../routes.php';
        //}

        // Publishing configs
        $this->publishes([__DIR__.'/../../config' => config_path()]);

        // Publishing views
        $this->loadViewsFrom(__DIR__.'/../../views', config('soda.hint_path'));

        // Loading translations
        // $this->loadTranslationsFrom(__DIR__ . '/../translations', config('soda.hint_path'));

        // Publishing public assets
        $this->publishes([__DIR__.'/../../public' => public_path('sodacms/sodacms')], 'public');

        // Publishing migrations
        $this->publishes([__DIR__.'/../../database' => database_path()], 'database');


        Blade::extend(function($value, $compiler)
        {
            $value = preg_replace('/(?<=\s)@switch\((.*)\)(\s*)@case\((.*)\)(?=\s)/', '<?php switch($1):$2case $3: ?>', $value);
            $value = preg_replace('/(?<=\s)@endswitch(?=\s)/', '<?php endswitch; ?>', $value);
            $value = preg_replace('/(?<=\s)@case\((.*)\)(?=\s)/', '<?php case $1: ?>', $value);
            $value = preg_replace('/(?<=\s)@default(?=\s)/', '<?php default: ?>', $value);
            $value = preg_replace('/(?<=\s)@break(?=\s)/', '<?php break; ?>', $value);
            return $value;
        });
    }

    /**
     * builds common interfaces for stuff.
     * TODO: move this somewhere sensible?
     */
    public function buildApp() {
        $this->app->singleton('application', function ($app) {
            return Application::find(1);
            //return('bonk');
            //return new FooBar($app['SomethingElse']);
        });

        //TODO: we should prbs be defining a 'soda' singleton that has
        //access to stuff like application etc that we can call from anywhere..
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
        $this->app->register('Soda\Providers\UploaderProvider');
        $this->app->register('Soda\Providers\RouteServiceProvider');
        $this->app->register('Franzose\ClosureTable\ClosureTableServiceProvider');
        $this->app->register('Franzose\ClosureTable\ClosureTableServiceProvider');

        //$this->app->bind('Soda', Soda::class);
        $this->app->bind('soda', function () {
            return new Soda\Soda(); //freaking cool-ass facades!
        });

        //$this->app->singleton('Soda', function(){
        //	return new Soda();
        //});
    }
}
