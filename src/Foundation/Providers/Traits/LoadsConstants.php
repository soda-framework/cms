<?php

namespace Soda\Cms\Foundation\Providers\Traits;

trait LoadsConstants
{
    protected $app;

    /**
     * Loads the given configuration into a global namespace.
     *
     * @param  string $path
     * @param  string $namespace
     *
     * @return void
     */
    protected function loadConstantsFrom($path, $namespace = null)
    {
        $constants = require $path;
        $prefix = $namespace ? "$namespace" : '';

        foreach ($constants as $constant => $value) {
            define(strtoupper("{$prefix}{$constant}"), $value);
        }
    }
}
