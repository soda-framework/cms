<?php
namespace Soda\Cms;

use Blade;
use Illuminate\Support\ServiceProvider;
use Soda\Cms\Console\CommandsServiceProvider;
use Soda\Cms\Forms\FormServiceProvider;
use Soda\Cms\Foundation\Application\ApplicationServiceProvider;
use Soda\Cms\Foundation\Blocks\BlocksServiceProvider;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Foundation\Fields\FieldsServiceProvider;
use Soda\Cms\Foundation\Pages\PageServiceProvider;
use Soda\Cms\Foundation\SodaInstance;
use Soda\Cms\Foundation\Support\Providers\AuthServiceProvider;
use Soda\Cms\Foundation\Support\Providers\RouteServiceProvider;
use Soda\Cms\Foundation\Support\Traits\SodaServiceProviderTrait;
use Soda\Cms\Foundation\Users\UserServiceProvider;
use Soda\Cms\Http\Matcher\RequestMatcherFacade;
use Soda\Cms\Http\Matcher\RequestMatcherServiceProvider;
use Soda\Cms\Menu\MenuFacade;
use Soda\Cms\Forms\FormFacade;
use Soda\Cms\Menu\MenuServiceProvider;
use Soda\Cms\Support\Facades\SodaFacade;
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
        $this->configure();

        // Publishing configs
        $this->publishes([__DIR__.'/../config' => config_path('soda')], 'soda.config');
        $this->publishes([__DIR__.'/../public' => public_path('soda/cms')], 'soda.assets');

        $this->loadTranslationsFrom(__DIR__.'/../translations', 'soda');
        $this->loadViewsFrom(__DIR__.'/../resources/views', config('soda.cms.hint'));
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

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
            BlocksServiceProvider::class,
            FieldsServiceProvider::class,
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
