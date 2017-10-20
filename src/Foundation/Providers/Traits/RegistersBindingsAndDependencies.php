<?php

namespace Soda\Cms\Foundation\Providers\Traits;

use Illuminate\Foundation\AliasLoader;

trait RegistersBindingsAndDependencies
{
    /**
     * Register bindings.
     *
     * @param array $bindings
     */
    public function registerBindings(array $bindings)
    {
        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Register instances.
     *
     * @param array $instances
     */
    public function registerInstances(array $instances)
    {
        foreach ($instances as $abstract => $concrete) {
            $this->app->instance($abstract, $concrete);
        }
    }

    /**
     * Register singletons.
     *
     * @param array $singletons
     */
    public function registerSingletons(array $singletons)
    {
        foreach ($singletons as $abstract => $concrete) {
            $this->app->instance($abstract, $concrete);
        }
    }

    /**
     * Register aliases.
     *
     * @param array $aliasGroup
     */
    public function registerAliases(array $aliasGroup)
    {
        foreach ($aliasGroup as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register facades.
     *
     * @param array $facades
     */
    protected function registerFacades(array $facades)
    {
        AliasLoader::getInstance($facades);
    }

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
}
