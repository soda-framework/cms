<?php

namespace Soda\Cms\Providers;

use Soda;
use Blade;
use Soda\Cms\Models\Role;
use Soda\Cms\Models\User;
use Soda\Cms\Console\Seed;
use Soda\Cms\Console\Setup;
use Soda\Cms\Console\Theme;
use Soda\Cms\Console\Assets;
use Soda\Cms\Console\Update;
use Soda\Cms\Console\Migrate;
use Soda\Cms\Models\Permission;
use Soda\Cms\Facades\SodaFacade;
use Zizaco\Entrust\EntrustFacade;
use Zofe\Rapyd\RapydServiceProvider;
use Zizaco\Entrust\EntrustServiceProvider;
use Soda\Cms\Components\Soda as SodaInstance;
use Franzose\ClosureTable\ClosureTableServiceProvider;

class SodaServiceProvider extends AbstractSodaServiceProvider
{
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
        require __DIR__.'/../helpers.php';

        $this->configure();

        // Publishing configs
        $this->publishes([__DIR__.'/../../config' => config_path()]);
        $this->publishes([__DIR__.'/../../database/migrations' => database_path('migrations')]);
        $this->publishes([__DIR__.'/../../public' => public_path('sodacms/sodacms')], 'soda.public');
        $this->loadViewsFrom(__DIR__.'/../../views', config('soda.hint_path'));

        $this->extendBlade();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigRecursivelyFrom(__DIR__.'/../../config/soda.php', 'soda');

        $this->registerDependencies([
            AuthServiceProvider::class,
            RouteServiceProvider::class,
            ClosureTableServiceProvider::class,
            RapydServiceProvider::class,
            EntrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Soda'    => SodaFacade::class,
            'Entrust' => EntrustFacade::class,
        ]);

        $this->app->singleton('soda', SodaInstance::class);

        $this->commands([
            Theme::class,
            Update::class,
            Assets::class,
            Setup::class,
            Migrate::class,
            Seed::class,
        ]);

        Soda::getFormBuilder()->registerMany(config('soda.fields'));
    }

    protected function configure()
    {
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

        Blade::directive('attributes', function ($attributes = null) {
            return '<?php if(@$attributes){
                    foreach($attributes as $key=>$attribute){
                            echo " $key=\\"$attribute\\" ";
                    }
                }?>';
        });
    }
}
