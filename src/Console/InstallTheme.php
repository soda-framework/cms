<?php

namespace Soda\Console;

use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Collection;

class InstallTheme extends Command
{
    protected $signature = 'soda:theme {--a|advanced : Use advanced options to set up your theme} {--e|extra : Include extra classes to build more complex theme functionality}';
    protected $description = 'Install an example Soda CMS Theme';
    protected $except = [];
    protected $attributes;

    public function handle()
    {
        $this->attributes = new Collection;
        $is_extra = $this->option('extra') ? true : false;

        if ($this->option('advanced')) {
            $this->configureAdvanced();
        } else {
            $this->configureSimple();
        }

        $this->installTheme($is_extra);

        $this->informCompletion();
    }

    protected function configureSimple()
    {
        $theme_name = ucfirst($this->anticipate('Please enter your theme name (using CamelCase)', ['SodaSite']));

        $folder = snake_case($theme_name);
        $this->attributes->put('folder', $folder);

        $namespace = $this->anticipateThemeClass($folder);
        $this->attributes->put('namespace', $namespace);

        $helper_class = $namespace.'Helper';
        $this->attributes->put('helper_class', $helper_class);

        $helper_class_facade_name = snake_case($helper_class);
        $this->attributes->put('helper_class_facade_name', $helper_class_facade_name);

        $package_name = $this->anticipatePackageName($folder);
        $this->attributes->put('package_name', $package_name);

        $view_hint = strtolower($folder);
        $this->attributes->put('view_hint', $view_hint);
    }

    protected function configureAdvanced()
    {
        $folder = $this->anticipate('Enter a theme folder name', ['soda_theme']);
        $this->attributes->put('folder', $folder);
        $class_guess = $this->anticipateThemeClass($folder);
        $package_guess = $this->anticipatePackageName($folder);

        $namespace = $this->anticipate('Enter a namespace', [$class_guess]);
        $this->attributes->put('namespace', $namespace);

        $helper_class = $this->anticipate('Enter a helper class name', [$namespace.'Helper']);
        $this->attributes->put('helper_class', $helper_class);

        $helper_class_facade_name = $this->anticipate('Enter a helper class facade name', [snake_case($helper_class)]);
        $this->attributes->put('helper_class_facade_name', $helper_class_facade_name);

        $package_name = $this->anticipate('Enter a package name (gulp and npm)', [$package_guess]);
        $this->attributes->put('package_name', $package_name);

        $view_hint = $this->anticipate('Enter a HintPath (views and routes)', [strtolower($folder)]);
        $this->attributes->put('view_hint', $view_hint);
    }

    protected function installTheme($extra = false)
    {
        $theme_base = __DIR__.'/../../themes/'.($extra ? 'advanced' : 'simple');

        $base_folder = $this->attributes->get('folder');
        $folder = './themes/'.$base_folder;
        $namespace = $this->attributes->get('namespace');
        $helper_class = $this->attributes->get('helper_class');
        $helper_class_facade_name = $this->attributes->get('helper_class_facade_name');
        $package_name = $this->attributes->get('package_name');
        $view_hint = $this->attributes->get('view_hint');

        mkdir($folder, 0755, true);
        $this->xcopy(__DIR__.'/../../themes/shared', $folder);
        $this->xcopy($theme_base, $folder);

        // We need to go through and find and replace everything in here with a different package name:
        rename($folder.'/src/Providers/SodaThemeServiceProvider.php', $folder.'/src/Providers/'.$namespace.'ServiceProvider.php');
        if ($extra) {
            rename($folder.'/src/Components/SodaHelperClass.php', $folder.'/src/Components/'.$helper_class.'.php');
            rename($folder.'/src/Facades/SodaHelperClassFacade.php', $folder.'/src/Facades/'.$helper_class.'Facade.php');
        }
        $this->info('Classes renamed.');

        $this->findAndReplace('SodaTheme', $namespace, $folder.'/src');
        $this->findAndReplace('soda-theme', $package_name, $folder);
        $this->findAndReplace('soda_theme_hint', $view_hint, $folder);
        if ($extra) {
            $this->findAndReplace('SodaHelperClass', $helper_class, $folder.'/src');
            $this->findAndReplace('soda_helper_class', $helper_class_facade_name, $folder.'/src');
        }
        $this->info('Theme references set.');

        $this->appendToComposerFile([
            'autoload' => [
                'psr-4' => [
                    "Themes\\{$namespace}\\" => "themes/$base_folder/src/",
                ],
            ],
        ]);
        $this->info('Composer config updated.');

        $this->call('optimize');
    }

    protected function informCompletion()
    {
        $namespace = $this->attributes->get('namespace');

        $this->info('Initial theme setup complete.');
        $this->info('Please follow the steps below to complete theme setup:');
        $this->comment("1. Add Themes/{$namespace}/Providers/{$namespace}ServiceProvider::class to your providers array");
        $this->comment('2. Run php artisan vendor:publish');

        return $this;
    }

    protected function anticipateThemeClass($string)
    {
        $class = ucfirst(camel_case($string));

        return preg_replace('/Theme$/', '', $class).'Theme';
    }

    protected function anticipatePackageName($string)
    {
        $package = snake_case($string);

        return str_replace('_', '-', $package);
    }

    protected function appendToComposerFile($config)
    {
        $file_path = base_path('composer.json');
        $composer_file = file_get_contents($file_path);
        $composer_json = json_decode($composer_file, true);

        file_put_contents($file_path, json_encode(array_replace_recursive($composer_json, $config), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $this;
    }

    protected function findAndReplace($needle, $replace, $haystack = './')
    {
        $d = new RecursiveDirectoryIterator($haystack);
        foreach (new RecursiveIteratorIterator($d, 1) as $path) {
            if (! (in_array($path->getPathname(), $this->except))) {
                if (is_file($path)) {
                    $orig_file = file_get_contents($path);
                    $new_file = str_replace($needle, $replace, $orig_file);
                    if ($orig_file != $new_file) {
                        file_put_contents($path, $new_file);
                        $this->info('Updated: '.$path);
                    }
                }
            } else {
                $this->info('Ignored:'.$path->getPathname());
            }
        }

        return $this;
    }

    /**
     * Copy a file, or recursively copy a folder and its contents.
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     *
     * @param       string $source Source path
     * @param       string $dest Destination path
     * @param       int $permissions New folder creation permissions
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
        if (! is_dir($dest)) {
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
