<?php

namespace Soda\Console;

use Illuminate\Console\Command;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;

class InstallTheme extends Command{

    protected $signature = 'soda:install_theme';
    protected $description = 'Install an example soda cms theme';
    protected $except = [
        //filename array in here.
    ];


    public function handle()
    {

        $folder = $this->ask('Enter a Folder: (./themes/soda_theme)');
        $namespace = $this->ask('Enter a Namespace: (SodaTheme)');
        $helper_class = $this->ask('Enter a Helper Class Name: (SodaHelperClass)');
        $helper_class_facade_name = $this->ask('Enter a Helper Class Facade Name: (soda_helper_class)');
        $prod_name = $this->ask('Enter a Package Name (gulp and npm): (soda-theme)');
        $theme_hint = $this->ask('Enter a HintPath (views and routes): (soda_theme_hint)');

        mkdir($folder, 0755, true);
        $this->xcopy(__DIR__.'/../../soda_theme', $folder);

        //we need to go through and find and replace everything in here with a different package name:
        rename($folder.'/src/Providers/SodaThemeServiceProvider.php', $folder.'/src/Providers/'.$namespace.'ServiceProvider.php');
        $this->info('renamed service provider');
        rename($folder.'/src/Components/SodaHelperClass.php', $folder.'/src/Components/'.$helper_class.'.php');
        $this->info('component renamed');
        rename($folder.'/src/Facades/SodaHelperClassFacade.php', $folder.'/src/Facades/'.$helper_class.'Facade.php');
        $this->info('facade renamed');

        $this->findAndReplace('SodaTheme', $namespace, $folder);
        $this->findAndReplace('SodaHelperClass', $helper_class, $folder);
        $this->findAndReplace('soda_helper_class', $helper_class_facade_name, $folder);
        $this->findAndReplace('soda-theme', $prod_name, $folder);
        $this->findAndReplace('soda_theme_hint', $theme_hint, $folder);

        $this->info('COMPLETE - steps left:');
        $this->info('1) add the theme into composer.json file');
        $this->info('2) run composer dump-autoload');
        $this->info('3) add in the Service Provider into your providers array');
        $this->info('4) php artisan vendor:publish');
    }


    public function findAndReplace( $needle, $replace, $haystack = "./" ){
        $d = new RecursiveDirectoryIterator( $haystack );
        foreach( new RecursiveIteratorIterator( $d, 1 ) as $path ){
            if(!(in_array($path->getPathname(), $this->except))){
                if( is_file( $path )){
                    $orig_file = file_get_contents($path);
                    $new_file = str_replace($needle, $replace ,$orig_file);
                    if($orig_file != $new_file){
                        file_put_contents($path, $new_file);
                        $this->info('Updated: '.$path);
                    }
                }
            }
            else{
                $this->info('Ignored:'. $path->getPathname());
            }

        }
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @param       int      $permissions New folder creation permissions
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

