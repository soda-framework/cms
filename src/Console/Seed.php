<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Seed extends Command
{
    protected $signature = 'soda:seed {--class=SodaSeeder : The class name of the root seeder}';
    protected $description = 'Seed the Soda Database';
    protected $except = [];

    /**
     * Runs seeds for Soda CMS, defaulting to 'SodaSeeder'.
     */
    public function handle()
    {
        $this->call('db:seed', [
            '--class' => $this->option('class'),
        ]);
    }
}
