<?php

namespace Soda\Cms\Support\Traits;

use Illuminate\Foundation\AliasLoader;

trait SodaServiceProviderTrait
{
    /**
     * Register dependencies.
     *
     * @param array $services
     */
    protected function registerDependencies(array $services)
    {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }

    /**
     * Register facades.
     *
     * @param array $facades
     */
    protected function registerFacades(array $facades)
    {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);
        }
    }

    /**
     * Merge the given configuration with the existing configuration, recursively.
     *
     * @param  string $path
     * @param  string $key
     *
     * @return void
     */
    protected function mergeConfigRecursivelyFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_replace_recursive(require $path, $config));
    }
}
