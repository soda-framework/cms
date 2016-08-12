<?php

namespace Soda\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

abstract class AbstractSodaServiceProvider extends ServiceProvider {

    /**
     * Register dependies conditionally (e.g. dev dependencies)
     *
     * @param array $services
     */
    protected function registerDependencies(array $services) {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }


    /**
     * @param array $facades
     */
    protected function registerFacades(array $facades) {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);
        }
    }
}
