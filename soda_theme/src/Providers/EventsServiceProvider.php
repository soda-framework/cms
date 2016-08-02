<?php
namespace Themes\SodaTheme\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class EventsServiceProvider extends ServiceProvider
{
    public $listen = [
        'Soda\Events\NavigationWasRendered' => [
            'Themes\SodaTheme\Listeners\MenuNavItems',
        ],
        'Soda\Events\DashboardWasRendered' => [
            'Themes\SodaTheme\Listeners\Dashboard',
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