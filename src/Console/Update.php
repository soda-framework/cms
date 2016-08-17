<?php

namespace Soda\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Update extends Command {

    protected $signature = 'soda:update {--a|all : Update everything} {--c|cms : Update cms} {--m|modules : Update modules} {--s|styles : Update styles/assets}';
    protected $description = 'Update your version of the Soda Framework';
    protected $except = [];
    protected $attributes;


    public function handle() {
        $this->attributes = new Collection;

        if ($this->option('all')) {
            $this->updateCms();
            $this->updateModules();
            $this->updateStyles();
        } else {
            if ($this->option('cms')) {
                $this->updateCms();
            }
            if ($this->option('modules')) {
                $this->updateModules();
            }
            if ($this->option('styles')) {
                $this->updateStyles();
            }
        }
    }

    protected function updateCms() {
        $this->info('Updating Soda CMS via Composer...');
        shell_exec('composer update sodacms/sodacms');
        $this->info('Soda CMS update complete.');
    }

    protected function updateModules() {
        $this->info('Updating Soda modules via Composer...');
        shell_exec('composer update sodacms/*');
        $this->info('Soda module update complete.');
    }

    protected function updateStyles() {
        $this->info('Updating Soda styles and assets...');
        $this->callSilent('vendor:publish', [
            '--force' => 1, '--tag' => 'soda.public'
        ]);
        $this->info('Soda styles and assets updated successfully.');
    }
}

