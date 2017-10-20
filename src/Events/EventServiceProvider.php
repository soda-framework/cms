<?php

namespace Soda\Cms\Events;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login'           => [
            'Soda\Cms\Events\Listeners\RecordLastLoginTimestamp',
        ],
        'Illuminate\Routing\Events\RouteMatched' => [
            'Soda\Cms\Events\Listeners\CatchSluggableRoutes',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
