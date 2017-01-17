<?php

namespace Soda\Cms\Providers;

use Soda\Cms\Console\Seed;
use Soda\Cms\Console\Setup;
use Soda\Cms\Console\Theme;
use Soda\Cms\Console\Assets;
use Soda\Cms\Console\Config;
use Soda\Cms\Console\Update;
use Soda\Cms\Console\Migrate;
use Illuminate\Support\ServiceProvider;

class CommandsServiceProvider extends ServiceProvider
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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Theme::class,
            Update::class,
            Assets::class,
            Setup::class,
            Migrate::class,
            Seed::class,
            Config::class,
        ]);
    }
}
