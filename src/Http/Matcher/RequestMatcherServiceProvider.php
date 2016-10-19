<?php
namespace Soda\Cms\Http\Matcher;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Support\Traits\SodaServiceProviderTrait;

class RequestMatcherServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('soda.request-matcher', RequestMatcher::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'soda.request-matcher',
        ];
    }
}
