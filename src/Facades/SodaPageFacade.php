<?php

namespace Soda\Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class SodaPageFacade extends Facade {
    /**
     *
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'soda.page';
    }

}
