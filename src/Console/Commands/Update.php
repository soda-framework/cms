<?php

namespace Soda\Cms\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends Command
{
    protected $signature = 'soda:update {--c|current : Current version to update from}';
    protected $description = 'Update your version of the Soda Framework';

    protected $updatesDir = 'updates';

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(method_exists($this, 'preUpdate')) {
            $this->preUpdate();
        }

        $namespace = "Soda\\Updater\\Cms\\";
        $updateFiles = new \RecursiveDirectoryIterator(base_path('vendor/soda-framework/cms/' . trim($this->updatesDir, '/')));
        $updateFiles = new \RecursiveCallbackFilterIterator(
            $updateFiles,
            function ($item) {
                return $item->getExtension() === 'php' ? true : false;
            }
        );

        foreach($updateFiles as $file) {
            require_once($file->getPathName());
            $className = $namespace . basename($file->getFilename(), '.php');
            if(class_exists($className)) {
                $updateClass = new $className;

                $updateClass->run();
            }
        }
    }

    protected function preUpdate()
    {
        $this->call('migrate', ['--path' => '/vendor/soda-framework/cms/migrations']);

        $this->call('soda:assets');
    }
}
