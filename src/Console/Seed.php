<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Seed extends Command
{
    protected $signature = 'soda:seed {--class=SodaSeeder}';
    protected $description = 'Seed the Soda Database';
    protected $except = [];

    public function handle()
    {
        $this->call('db:seed', [
            '--class' => $this->argument('class'),
        ]);
    }
}
