<?php
namespace Soda\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Soda\Cms\Foundation\Pages\PageBuilder;
use Soda\Cms\Support\Facades\SodaPageFacade;
use Soda\Cms\Support\Traits\SodaServiceProviderTrait;

class PageServiceProvider extends ServiceProvider
{
    use SodaServiceProviderTrait;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->registerFacades([
            'SodaPage' => SodaPageFacade::class,
        ]);
        $this->app->bind('soda.page', PageBuilder::class);
    }
}
