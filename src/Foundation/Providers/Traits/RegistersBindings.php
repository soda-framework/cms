<?php

namespace Soda\Cms\Foundation\Providers\Traits;

trait RegistersBindings
{
    public function registerBindings(array $bindings)
    {
        foreach ($bindings as $binding => $implementation) {
            $this->app->alias($binding, $implementation['abstract']);

            if (isset($implementation['instance']) && $implementation['instance']) {
                $this->app->singleton($binding, $implementation['concrete']);
            } else {
                $this->app->bind($binding, $implementation['concrete']);
            }
        }
    }
}
