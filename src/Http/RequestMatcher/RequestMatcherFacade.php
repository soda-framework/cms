<?php

namespace Soda\Cms\Http\RequestMatcher;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class RequestMatcherFacade extends Facade
{
    /**
     *
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'soda.request-matcher';
    }

}
