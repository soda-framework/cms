<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Assets extends Command
{
    protected $signature = 'soda:assets';
    protected $description = 'Update assets for the Soda Framework';
    protected $except = [];

    /**
     * Force publishes Soda CMS assets.
     */
    public function handle()
    {
        $this->info('Updating Soda styles and assets...');
        $this->callSilent('vendor:publish', [
            '--force' => 1,
            '--tag'   => 'soda.assets',
        ]);
        $this->info('Soda styles and assets updated successfully.');
    }
}
