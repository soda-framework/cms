<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Config extends Command
{

    protected $signature = 'soda:config';
    protected $description = 'Update config for the Soda Framework';
    protected $except = [];

    /**
     * Force publishes Soda CMS assets
     */
    public function handle()
    {
        $this->info('Updating Soda config...');
        $this->callSilent('vendor:publish', [
            '--force' => 1,
            '--tag'   => 'soda.config',
        ]);
        $this->info('Soda config updated successfully.');
    }
}

