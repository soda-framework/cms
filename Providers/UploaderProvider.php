<?php

namespace Soda\Providers;

use Illuminate\Support\ServiceProvider;
use Soda\Components\Uploader\Uploader as Uploader;
use Soda\Components\Uploader\S3 as S3;

class UploaderProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = FALSE;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
    }

    public function register()
    {
        \Illuminate\Foundation\AliasLoader::getInstance()->alias('Uploader', '\Soda\Facades\UploaderFacade');
        // if there's no config set, assume S3
        $upload_destination = isset(config('soda')['upload_destination']) ? config('soda')['upload_destination'] : NULL;
        if($upload_destination == 's3')
        {
            $this->app->singleton('s3', function ()
            {
                return new S3();
            });
        }
        elseif(is_null($upload_destination) || $upload_destination == 'default')
        {
            // should check for upload_destination == default here at some point
            $this->app->singleton('uploader', function ()
            {
                return new Uploader();
            });
        }

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
    }
}
