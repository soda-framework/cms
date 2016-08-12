<?php

namespace Soda\Providers;

use Illuminate\Foundation\AliasLoader;
use Soda\Components\Uploader\S3 as S3;
use Soda\Components\Uploader\Uploader as Uploader;
use Soda\Facades\UploaderFacade;

class UploaderProvider extends AbstractSodaServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot() {
    }

    public function register() {

        $this->registerFacades([
            'Uploader' => UploaderFacade::class,
        ]);

        // if there's no config set, assume S3
        $upload_destination = $this->app->config->get('soda.upload_destination');
        switch ($upload_destination) {
            case 's3':
                $this->app->singleton('s3', function () {
                    return new S3;
                });
                break;
            case 'default':
            default:
                $this->app->singleton('uploader', function () {
                    return new Uploader;
                });
            break;
        }

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
    }
}
