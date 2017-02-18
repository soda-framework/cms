<?php

namespace Soda\Cms\Events\Listeners;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\RouteAction;
use Soda\Cms\Support\Facades\RequestMatcher;

class CatchSluggableRoutes
{
    /**
     * Handle the event.
     *
     * @param RouteMatched $event
     */
    public function handle(RouteMatched $event)
    {
        if ($event->route->getName() == 'soda.page.match') {
            $action = RequestMatcher::match($event->request);

            $event->route->setAction(RouteAction::parse($event->route->uri, $action));
        }
    }
}
