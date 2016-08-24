<?php
namespace Soda\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Soda;
use Blade;
use Franzose\ClosureTable\ClosureTableServiceProvider;
use Soda\Cms\Components\Forms\FormBuilder;
use Soda\Cms\Components\Forms\FormFieldRegistrar;
use Soda\Cms\Components\Menu\MenuBuilder;
use Soda\Cms\Components\Pages\PageBuilder;
use Soda\Cms\Components\Soda as SodaInstance;
use Soda\Cms\Console\Assets;
use Soda\Cms\Console\Migrate;
use Soda\Cms\Console\Seed;
use Soda\Cms\Console\Setup;
use Soda\Cms\Console\Theme;
use Soda\Cms\Console\Update;
use Soda\Cms\Facades\SodaFacade;
use Soda\Cms\Facades\SodaFormFacade;
use Soda\Cms\Facades\SodaMenuFacade;
use Soda\Cms\Models\Permission;
use Soda\Cms\Models\Role;
use Soda\Cms\Models\User;
use Storage;
use Zofe\Rapyd\RapydServiceProvider;
use Zizaco\Entrust\EntrustServiceProvider;
use Zizaco\Entrust\EntrustFacade;

class SodaServiceProvider extends ServiceProvider {
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
    public function boot() {
        require(__DIR__ . '/../helpers.php');

        $this->configure();

        // Publishing configs
        $this->publishes([__DIR__ . '/../../config' => config_path()]);
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')]);
        $this->publishes([__DIR__ . '/../../public' => public_path('sodacms/sodacms')], 'soda.public');
        $this->loadViewsFrom(__DIR__ . '/../../views', config('soda.hint_path'));

        $this->extendBlade();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigRecursivelyFrom(__DIR__ . '/../../config/soda.php', 'soda');

        $this->registerDependencies([
            AuthServiceProvider::class,
            RouteServiceProvider::class,
            ClosureTableServiceProvider::class,
            RapydServiceProvider::class,
            EntrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Soda'     => SodaFacade::class,
            'SodaMenu' => SodaMenuFacade::class,
            'SodaForm' => SodaFormFacade::class,
            'Entrust'  => EntrustFacade::class,
        ]);

        $this->app->bind('soda', function($app) {
            return new SodaInstance($app['soda.form'], $app['soda.page'], $app['soda.menu']);
        });

        $this->app->bind('soda.form.registrar', function($app) {
            return new FormFieldRegistrar($app['config']->get('soda.fields'));
        });

        $this->app->bind('soda.form', function($app) {
            return new FormBuilder($app['soda.form.registrar']);
        });

        $this->app->bind('soda.menu', MenuBuilder::class);

        $this->app->bind('soda.page', PageBuilder::class);

        $this->commands([
            Theme::class,
            Update::class,
            Assets::class,
            Setup::class,
            Migrate::class,
            Seed::class,
        ]);
    }

    protected function configure() {
        $this->app->config->set('entrust.role', Role::class);
        $this->app->config->set('entrust.permission', Permission::class);

        $this->app->config->set('auth.providers.soda', [
            'driver' => 'eloquent',
            'model'  => User::class,
        ]);

        $this->app->config->set('auth.guards.soda', [
            'driver'   => 'session',
            'provider' => 'soda',
        ]);

        $this->app->config->set('auth.passwords.soda', [
            'provider' => 'soda',
            'email'    => 'auth.emails.password',
            'table'    => 'password_resets',
            'expire'   => 60,
        ]);

        $this->app->config->set('filesystems.disks.soda.public', [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'visibility' => 'public',
        ]);
    }

    protected function extendBlade() {
        Blade::extend(function ($value, $compiler) {
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
}
