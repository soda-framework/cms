<?php

namespace Soda\Cms\InterfaceBuilder\Dashboard;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('soda.dashboard', function ($app) {
            $dashboardBuilder = new DashboardBuilder;

            $dashboardBuilder->addBlockToRow(1, 'default', function () {
                return soda_cms_view('partials.dashboard.default-block');
            });

            $dashboardBuilder->addBlockToRow(1, 'quicklinks', function () {
                return soda_cms_view('partials.dashboard.quicklinks');
            });

            return $dashboardBuilder;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'soda.dashboard',
        ];
    }
}
