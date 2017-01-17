<?php

namespace Soda\Cms\Console;

use Illuminate\Console\Command;

class Update extends Command
{
    protected $signature = 'soda:update {--a|all : Update everything} {--c|cms : Update cms} {--m|modules : Update modules}';
    protected $description = 'Update your version of the Soda Framework';

    public function handle()
    {
        if ($this->option('all')) {
            $this->updateCms();
            $this->updateModules();
        } else {
            if ($this->option('cms')) {
                $this->updateCms();
            }
            if ($this->option('modules')) {
                $this->updateModules();
            }
        }
    }

    /**
     * Update CMS via composer.
     */
    protected function updateCms()
    {
        $this->info('Updating Soda CMS via Composer...');
        shell_exec('composer update soda-framework/cms');
        $this->info('Soda CMS update complete.');

        $this->call('soda:assets');
    }

    /**
     * Update all Soda-vendored modules.
     */
    protected function updateModules()
    {
        $this->info('Updating Soda modules via Composer...');
        shell_exec('composer update soda-framework/*');
        $this->info('Soda module update complete.');
    }
}
