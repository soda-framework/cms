<?php
namespace Soda\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Observers\BlockObserver;
use Soda\Cms\Models\Observers\DynamicObserver;
use Soda\Cms\Models\Observers\PageObserver;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\PageType;
use Soda\Cms\Models\Permission;
use Soda\Cms\Models\Role;
use Soda\Cms\Models\User;
use Franzose\ClosureTable\ClosureTableServiceProvider;
use Zizaco\Entrust\EntrustServiceProvider;
use Zizaco\Entrust\EntrustFacade;

class EloquentServiceProvider extends ServiceProvider {
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
        $this->configure();
        $this->bootObservers();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {

        $this->registerDependencies([
            ClosureTableServiceProvider::class,
            EntrustServiceProvider::class,
        ]);

        $this->registerFacades([
            'Entrust'  => EntrustFacade::class,
        ]);
    }

    protected function bootObservers() {
        Block::observe(BlockObserver::class);
        Page::observe(PageObserver::class);
        BlockType::observe(DynamicObserver::class);
        PageType::observe(DynamicObserver::class);
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
    }
}
