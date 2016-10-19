<?php
namespace Soda\Cms\Console;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Console\Commands\Assets;
use Soda\Cms\Console\Commands\Config;
use Soda\Cms\Console\Commands\Migrate;
use Soda\Cms\Console\Commands\Seed;
use Soda\Cms\Console\Commands\Setup;
use Soda\Cms\Console\Commands\Theme;
use Soda\Cms\Console\Commands\Update;

class CommandsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Update::class  => 'soda.command.update',
        Assets::class  => 'soda.command.assets',
        Migrate::class => 'soda.command.migrate',
        Seed::class    => 'soda.command.seed',
        Config::class  => 'soda.command.config',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        Setup::class => 'soda.command.setup',
        Theme::class => 'soda.command.theme',
    ];

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
        $this->registerCommands($this->commands);

        $this->registerCommands($this->devCommands);
    }

    /**
     * Register the given commands.
     *
     * @param  array $commands
     *
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach ($commands as $class => $command) {
            $method = "register{$command}Command";

            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], []);
            } else {
                $this->app->singleton($command, $class);
            }
        }

        $this->commands(array_values($commands));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        if ($this->app->environment('production')) {
            return array_values($this->commands);
        } else {
            return array_merge(array_values($this->commands), array_values($this->devCommands));
        }
    }
}
