<?php

namespace Soda\Cms\Console\Commands;

use Illuminate\Console\Command;
use League\Flysystem\FileExistsException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Theme extends Command
{

    protected $signature = 'soda:theme {name?}';
    protected $description = 'Install an example Soda CMS Theme';
    protected $folder;
    protected $namespace;

    public function handle()
    {
        $this->configureTheme();
        $this->installTheme();
    }

    /**
     * Determine a suitable folder name and namespace from the user's input
     */
    protected function configureTheme()
    {
        $themeName = $this->argument('name');
        $themeName = $themeName ? $themeName : ucfirst($this->ask('Please enter your theme name (using CamelCase)', 'SodaSite'));

        $this->folder = str_slug(snake_case($themeName), '-');

        $namespace = $this->anticipateThemeClass($this->folder);
        $this->namespace = $namespace;
    }

    /**
     * Move and rename theme files based on user input
     */
    protected function installTheme()
    {
        $theme_base = __DIR__.'/../../../stubs/theme';

        $path = base_path('themes/'.$this->folder);

        if (file_exists($path)) {
            throw new FileExistsException($path);
        }

        mkdir($path, 0755, true);
        $this->xcopy($theme_base, $path);

        // We need to go through and find and replace everything in here with a different package name:
        rename($path.'/src/SodaExampleThemeServiceProvider.php', $path.'/src/'.$this->namespace.'ThemeServiceProvider.php');
        $this->info('Classes renamed.');

        $this->findAndReplace('SodaExample', $this->namespace, $path.'/src');
        $this->findAndReplace('SodaExample', $this->namespace, $path.'/config');
        $this->findAndReplace('soda-example', $this->folder, $path);

        $this->info('Theme references set.');

        $this->appendToComposerFile([
            "autoload" => [
                "psr-4" => [
                    "Themes\\{$this->namespace}\\" => "themes/{$this->folder}/src/",
                ],
            ],
        ]);

        $this->call('optimize');
        $this->info('Composer config updated.');

        $this->addServiceProvider("Themes\\{$this->namespace}\\{$this->namespace}ThemeServiceProvider::class");
        $this->info('Service provider added.');

        require_once($path.'/src/'.$this->namespace.'ThemeServiceProvider.php');
        app()->register("Themes\\{$this->namespace}\\{$this->namespace}ThemeServiceProvider");
        $this->info('Service provider registered.');

        $this->call('vendor:publish', [
            '--tag' => $this->folder . '.config'
        ]);

        $this->call('vendor:publish', [
            '--tag' => $this->folder . '.public'
        ]);

        $this->info('Theme assets published.');

        $this->info('Done!');
    }

    /**
     * Guess a suitable classname based on the string provided
     *
     * @param $string
     *
     * @return string
     */
    protected function anticipateThemeClass($string)
    {
        return studly_case($string);
    }

    /**
     * Merge config into the compser.json file
     *
     * @param $config
     *
     * @return $this
     */
    protected function appendToComposerFile($config)
    {
        $file_path = base_path('composer.json');
        $composer_file = file_get_contents($file_path);
        $composer_json = json_decode($composer_file, true);

        file_put_contents($file_path, json_encode(array_replace_recursive($composer_json, $config), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $this;
    }

    /**
     * Add a service provider to config/app.php
     *
     * @param $serviceProvider
     */
    protected function addServiceProvider($serviceProvider)
    {
        $application_config = config_path('app.php');

        if (file_exists($application_config)) {
            $contents = file_get_contents($application_config);

            $old_provider = "Soda\\Cms\\SodaServiceProvider::class,";
            $provider_replacement = "$old_provider\n        $serviceProvider,";

            $contents = str_replace($old_provider, $provider_replacement, $contents);

            file_put_contents($application_config, $contents);
        }
    }

    /**
     * Find and replace two strings recursively from within a path
     *
     * @param        $needle
     * @param        $replace
     * @param string $haystack
     *
     * @return $this
     */
    protected function findAndReplace($needle, $replace, $haystack = "./")
    {
        $d = new RecursiveDirectoryIterator($haystack);
        foreach (new RecursiveIteratorIterator($d, 1) as $path) {
            if (is_file($path)) {
                $orig_file = file_get_contents($path);
                $new_file = str_replace($needle, $replace, $orig_file);
                if ($orig_file != $new_file) {
                    file_put_contents($path, $new_file);
                    $this->info('Updated: '.$path);
                }
            }
        }

        return $this;
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     *
     * @param       string $source      Source path
     * @param       string $dest        Destination path
     * @param       int    $permissions New folder creation permissions
     *
     * @return      bool     Returns true on success, false on failure
     */
    public function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();

        return true;
    }
}

