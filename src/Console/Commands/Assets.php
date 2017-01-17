<?php

namespace Soda\Cms\Console\Commands;

use Illuminate\Console\Command;

class Assets extends Command
{
    protected $signature = 'soda:assets {--l|lol=test}';
    protected $description = 'Update assets for the Soda Framework';

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
