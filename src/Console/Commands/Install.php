<?php

namespace Soda\Cms\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'soda:install';
    protected $description = 'Install the Soda CMS package';

    /**
     * Runs all database migrations for Soda.
     */
    public function handle()
    {
        $this->call('migrate', ['--path' => '/vendor/soda-framework/cms/migrations']);

        $this->call('db:seed', ['--class' => 'Soda\\Cms\\Foundation\\Setup\\SetupApplication']);
        $this->call('db:seed', ['--class' => 'Soda\\Cms\\Foundation\\Setup\\SetupRoles']);
        $this->call('db:seed', ['--class' => 'Soda\\Cms\\Foundation\\Setup\\SetupUsers']);
        $this->call('db:seed', ['--class' => 'Soda\\Cms\\Foundation\\Setup\\SetupPermissions']);

        $this->call('soda:assets');
    }
}
