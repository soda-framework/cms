<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Migrate extends Command
{

    protected $signature = 'soda:migrate';
    protected $description = 'Migrate the Soda Database';
    protected $except = [];

    /**
     * Runs all database migrations for Soda
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => '/vendor/soda-framework/cms/database/migrations',
        ]);
    }
}
