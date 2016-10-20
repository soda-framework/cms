<?php

namespace Soda\Cms\Foundation\Providers\Traits;

trait MergesConfigRecursively
{
    protected $app;

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
