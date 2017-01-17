<?php

namespace Soda\Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Foundation\Application
 */
class UploaderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        if (config('soda')['upload_destination'] == 's3') {
            return 's3';
        } else {
            return 'uploader';
        }
    }
}
