<?php
namespace Soda\Cms;

use Blade;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Console\CommandsServiceProvider;
use Soda\Cms\Forms\FormServiceProvider;
use Soda\Cms\Database\Application\ApplicationServiceProvider;
use Soda\Cms\Database\Blocks\BlockServiceProvider;
use Soda\Cms\Database\Fields\FieldsServiceProvider;
use Soda\Cms\Database\Pages\PageServiceProvider;
use Soda\Cms\Foundation\SodaInstance;
use Soda\Cms\Foundation\Providers\AuthServiceProvider;
use Soda\Cms\Foundation\Providers\RouteServiceProvider;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;
use Soda\Cms\Database\Users\UserServiceProvider;
use Soda\Cms\Http\RequestMatcher\RequestMatcherFacade;
use Soda\Cms\Http\RequestMatcher\RequestMatcherServiceProvider;
use Soda\Cms\Menu\MenuFacade;
use Soda\Cms\Forms\FormFacade;
use Soda\Cms\Menu\MenuServiceProvider;
use Soda\Cms\Support\Facades\SodaFacade;
use Zofe\Rapyd\RapydServiceProvider;

class SodaServiceProvider extends ServiceProvider
{
    use RegistersBindingsAndDependencies;
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
        $this->configure();

        // Publishing configs
        $this->publishes([__DIR__.'/../config' => config_path('soda')], 'soda.config');
        $this->publishes([__DIR__.'/../public' => public_path('soda/cms')], 'soda.assets');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'soda');
        $this->loadViewsFrom(__DIR__.'/../resources/views', config('soda.cms.hint'));

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
        require_once(__DIR__.'/Foundation/helpers.php');

        $this->mergeConfigFrom(__DIR__.'/../config/cms.php', 'soda.cms');
        $this->mergeConfigFrom(__DIR__.'/../config/cache.php', 'soda.cache');
        $this->mergeConfigFrom(__DIR__.'/../config/upload.php', 'soda.upload');

        $this->registerDependencies([
            AuthServiceProvider::class,
            FormServiceProvider::class,
            CommandsServiceProvider::class,
            RequestMatcherServiceProvider::class,
            MenuServiceProvider::class,

            RapydServiceProvider::class,

            ApplicationServiceProvider::class,
            FieldsServiceProvider::class,
            BlockServiceProvider::class,
            PageServiceProvider::class,
            UserServiceProvider::class,
        ]);

        $this->registerFacades([
            'Soda'        => SodaFacade::class,
            'SodaForm'    => FormFacade::class,
            'SodaMenu'    => MenuFacade::class,
            'SodaMatcher' => RequestMatcherFacade::class,
        ]);

        $this->app->singleton('soda', function ($app) {
            return new SodaInstance($app);
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
            return '<?php if(isset('.$expression.')) {
                                foreach('.$expression.' as $key => $attribute) {
                                    echo " $key=\"$attribute\"";
                                }
                          }
            ?>';
        });
    }
}
