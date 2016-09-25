<?php
namespace Soda\Cms\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Components\Soda as SodaInstance;
use Soda\Cms\Facades\SodaFacade;
use Zofe\Rapyd\RapydServiceProvider;

class SodaServiceProvider extends ServiceProvider
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
        require(__DIR__.'/../helpers.php');

        $this->configure();

        // Publishing configs
        $this->publishes([__DIR__.'/../../config' => config_path('soda')], 'soda.config');
        //$this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')], 'soda.migrations');
        $this->publishes([__DIR__.'/../../public' => public_path('soda/cms')], 'soda.assets');
        $this->loadViewsFrom(__DIR__.'/../../views', config('soda.cms.hint'));

        $this->extendBlade();

        $this->registerDependencies([
            RouteServiceProvider::class,
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/cms.php', 'soda.cms');
        $this->mergeConfigFrom(__DIR__.'/../../config/fields.php', 'soda.fields');
        $this->mergeConfigFrom(__DIR__.'/../../config/upload.php', 'soda.upload');

        $this->registerDependencies([
            FormServiceProvider::class,
            CommandsServiceProvider::class,
            EloquentServiceProvider::class,
            PageServiceProvider::class,
            MenuServiceProvider::class,
            RapydServiceProvider::class,
        ]);

        $this->registerFacades([
            'Soda' => SodaFacade::class,
        ]);

        $this->app->bind('soda', function ($app) {
            return new SodaInstance($app['soda.form'], $app['soda.page'], $app['soda.menu']);
        });
    }

    protected function configure()
    {
        $this->app->config->set('filesystems.disks.soda.public', [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'visibility' => 'public',
        ]);
    }

    protected function extendBlade()
    {
        Blade::extend(function ($value, $compiler) {
            $value = preg_replace('/(?<=\s)@switch\((.*)\)(\s*)@case\((.*)\)(?=\s)/', '<?php switch($1):$2case $3: ?>', $value);
            $value = preg_replace('/(?<=\s)@endswitch(?=\s)/', '<?php endswitch; ?>', $value);
            $value = preg_replace('/(?<=\s)@case\((.*)\)(?=\s)/', '<?php case $1: ?>', $value);
            $value = preg_replace('/(?<=\s)@default(?=\s)/', '<?php default: ?>', $value);
            $value = preg_replace('/(?<=\s)@break(?=\s)/', '<?php break; ?>', $value);

            return $value;
        });

        Blade::directive('attr', function ($expression = null) {
            return '<?php if(isset'.$expression.') {
                                foreach('.$expression.' as $key => $attribute) {
                                    echo " $key=\"$attribute\"";
                                }
                          }
            ?>';
        });
    }
}
