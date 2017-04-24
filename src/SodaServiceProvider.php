<?php

namespace Soda\Cms;

use Soda\Cms\Support\Facades\Soda;
use Zofe\Rapyd\RapydServiceProvider;
use Illuminate\Support\Facades\Blade;
use Soda\Cms\Foundation\SodaInstance;
use Illuminate\Support\ServiceProvider;
use Laratrust\LaratrustServiceProvider;
use Soda\Cms\Foundation\DraftingHandler;
use Soda\Cms\Events\EventServiceProvider;
use Soda\Cms\Database\PageServiceProvider;
use Soda\Cms\Database\UserServiceProvider;
use Soda\Cms\Routing\RouteServiceProvider;
use Soda\Cms\Database\BlockServiceProvider;
use Soda\Cms\Database\FieldServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Soda\Cms\Console\CommandsServiceProvider;
use Rutorika\Sortable\SortableServiceProvider;
use Soda\Cms\Database\ApplicationServiceProvider;
use Soda\Cms\Foundation\Providers\AuthServiceProvider;
use Soda\Cms\InterfaceBuilder\Menu\MenuServiceProvider;
use Soda\Cms\InterfaceBuilder\Forms\FormServiceProvider;
use Soda\Cms\InterfaceBuilder\InterfaceBuilderServiceProvider;
use Soda\Cms\Http\RequestMatcher\RequestMatcherServiceProvider;
use Soda\Cms\InterfaceBuilder\Breadcrumb\BreadcrumbServiceProvider;
use Soda\Cms\Foundation\Providers\Traits\RegistersBindingsAndDependencies;

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
        $this->publishes([__DIR__.'/../config/publish' => config_path('soda')], 'soda.config');
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
        require_once __DIR__.'/helpers.php';

        // Override Zofe DataSet
        class_alias('Soda\Cms\Support\DataSet', 'Zofe\Rapyd\DataSet');
        class_alias('Soda\Cms\Support\DataField', 'Zofe\Rapyd\DataForm\Field\Field');

        $this->mergeConfigFrom(__DIR__.'/../config/publish/cms.php', 'soda.cms');
        $this->mergeConfigFrom(__DIR__.'/../config/publish/cache.php', 'soda.cache');
        $this->mergeConfigFrom(__DIR__.'/../config/publish/upload.php', 'soda.upload');
        $this->mergeConfigFrom(__DIR__.'/../config/sortable.php', 'soda.sortable');

        $this->registerDependencies([
            // Soda Dependencies
            AuthServiceProvider::class,
            EventServiceProvider::class,
            RequestMatcherServiceProvider::class,
            CommandsServiceProvider::class,
            InterfaceBuilderServiceProvider::class,
            MenuServiceProvider::class,
            BreadcrumbServiceProvider::class,
            FormServiceProvider::class,

            // Plugins
            RapydServiceProvider::class,
            SortableServiceProvider::class,
            LaratrustServiceProvider::class,
            ImageServiceProvider::class,

            // Deferred Model Providers
            ApplicationServiceProvider::class,
            FieldServiceProvider::class,
            BlockServiceProvider::class,
            PageServiceProvider::class,
            UserServiceProvider::class,
        ]);

        $this->registerFacades([
            'Soda'          => Soda::class,
        ]);

        $this->app->singleton('soda', function ($app) {
            return new SodaInstance($app);
        });

        $this->app->singleton('soda.drafting', function ($app) {
            return new DraftingHandler();
        });
    }

    protected function configure()
    {
        $this->app->config->set('filesystems.disks.soda.public', [
            'driver'     => 'local',
            'root'       => public_path($this->app->config->get('soda.upload.folder')),
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
