<?php
namespace Soda\Providers;

use Illuminate\Foundation\AliasLoader;
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
        //TODO: I don't know if this is ok to do or not - just hoping for the best here really

        config()->set('auth.providers.soda', [
            'driver' => 'eloquent',
            'model' => \Soda\Models\User::class
        ]);
        config()->set('auth.guards.soda', [
            'driver' => 'session',
            'provider' => 'soda',
        ]);

        config()->set('auth.passwords.soda', [
            'provider' => 'soda',
            'email' => 'auth.emails.password',
            'table' => 'password_resets',
            'expire' => 60,
        ]);

        //config()->set('auth.defaults.guard', 'soda');


        $this->buildApp();

        // Loading routes
        //if (!$this->app->routesAreCached()) {
        //  //require __DIR__ . '/../routes.php';
        //}

        // Publishing configs
        $this->publishes([__DIR__.'/../../config' => config_path()]);
        $this->publishes([__DIR__.'/../../database' => database_path()]);

        // Publishing views
        $this->loadViewsFrom(__DIR__.'/../../views', config('soda.hint_path'));

        // Loading translations
        // $this->loadTranslationsFrom(__DIR__ . '/../translations', config('soda.hint_path'));

        // Publishing public assets
        $this->publishes([__DIR__.'/../../public' => public_path('sodacms/sodacms')], 'soda.public');



        Blade::extend(function($value, $compiler)
        {
            $value = preg_replace('/(?<=\s)@switch\((.*)\)(\s*)@case\((.*)\)(?=\s)/', '<?php switch($1):$2case $3: ?>', $value);
            $value = preg_replace('/(?<=\s)@endswitch(?=\s)/', '<?php endswitch; ?>', $value);
            $value = preg_replace('/(?<=\s)@case\((.*)\)(?=\s)/', '<?php case $1: ?>', $value);
            $value = preg_replace('/(?<=\s)@default(?=\s)/', '<?php default: ?>', $value);
            $value = preg_replace('/(?<=\s)@break(?=\s)/', '<?php break; ?>', $value);
            return $value;
        });

        Blade::directive('attributes', function ($attributes = null) {
            return '<?php if(@$attributes){
                    foreach($attributes as $key=>$attribute){
                            echo " $key=\\"$attribute\\" ";
                    }
                }?>';
        });

    }

    /**
     * builds common interfaces for stuff.
     * TODO: move this somewhere sensible?
     */
    public function buildApp() {
        //$this->app->singleton('application', function ($app) {
        //    return Application::find(1);
        //    //return('bonk');
        //    //return new FooBar($app['SomethingElse']);
        //});

        //TODO: we should prbs be defining a 'soda' singleton that has
        //access to stuff like application etc that we can call from anywhere..
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
        $this->registerDependencies([
            AuthServiceProvider::class,
            UploaderProvider::class,
            RouteServiceProvider::class,
            \Franzose\ClosureTable\ClosureTableServiceProvider::class,
            \Zofe\Rapyd\RapydServiceProvider::class,
            \Creativeorange\Gravatar\GravatarServiceProvider::class
        ]);


        $this->registerFacades([
            'Gravatar' => Creativeorange\Gravatar\Facades\Gravatar::class,
        ]);

        ////$this->app->bind('Soda', Soda::class);
        //$this->app->bind('soda', function () {
        //    return new Soda\Soda();
        //});

        $this->app->singleton('soda', function(){
            return new Soda\Soda();
        });
    }

    /**
     * Register dependies conditionally (e.g. dev dependencies)
     *
     * @param array $services
     */
    public function registerDependencies(array $services) {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }


    /**
     * @param array $facades
     */
    public function registerFacades(array $facades) {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);
        }
    }
}
