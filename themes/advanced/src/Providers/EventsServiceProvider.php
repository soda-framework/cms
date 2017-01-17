<?php

namespace Themes\SodaExample\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventsServiceProvider extends ServiceProvider
{
    public $listen = [
        'Soda\Cms\Events\NavigationWasRendered' => [
            'Themes\SodaExample\Listeners\MenuNavItems',
        ],
        'Soda\Cms\Events\DashboardWasRendered'  => [
            'Themes\SodaExample\Listeners\Dashboard',
        ],
    ];

    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }

    public function register()
    {
    }
}
