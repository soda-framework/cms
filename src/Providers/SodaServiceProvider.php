<?php
namespace Soda\Cms\Providers;

use Blade;
use Franzose\ClosureTable\ClosureTableServiceProvider;
use Soda\Cms\Components\Soda;
use Soda\Cms\Console\Assets;
use Soda\Cms\Console\Theme;
use Soda\Cms\Console\Update;
use Soda\Cms\Facades\SodaFacade;
use Soda\Cms\Models\User;
use Storage;
use Zofe\Rapyd\RapydServiceProvider;
use Zizaco\Entrust\EntrustServiceProvider;
use Zizaco\Entrust\EntrustFacade;

class SodaServiceProvider extends AbstractSodaServiceProvider {
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
            'Soda'    => SodaFacade::class,
            'Entrust' => EntrustFacade::class,
        ]);

        $this->app->singleton('soda', Soda::class);

        $this->commands([
            Theme::class,
            Update::class,
            Assets::class,
        ]);
    }

    protected function configure() {
        $this->app->config->set('entrust.role', 'Soda\Cms\Models\Role');
        $this->app->config->set('entrust.permission', 'Soda\Cms\Models\Permission');

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
