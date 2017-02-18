<?php

namespace Soda\Cms\Events\Listeners;

use Illuminate\Http\Request;
use Illuminate\Routing\RouteAction;
use Illuminate\Session\SessionManager;
use Illuminate\Routing\Events\RouteMatched;
use Soda\Cms\Support\Facades\RequestMatcher;
use Illuminate\Contracts\Encryption\Encrypter;

class CatchSluggableRoutes
{
    protected $sessionManager;
    protected $encrypter;

    /**
     * Create a new session middleware.
     *
     * @param  \Illuminate\Session\SessionManager $sessionManager
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     */
    public function __construct(SessionManager $sessionManager, Encrypter $encrypter)
    {
        $this->sessionManager = $sessionManager;
        $this->encrypter = $encrypter;
    }

    /**
     * Handle the event.
     *
     * @param RouteMatched $event
     */
    public function handle(RouteMatched $event)
    {
        if ($event->route->getName() == 'soda.page.match') {
            $event->request->setLaravelSession(
                $session = $this->startSession($event->request)
            );

            $action = RequestMatcher::match($event->request);

            $event->route->setAction(RouteAction::parse($event->route->uri, $action));
        }
    }

    /**
     * Start the session for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function startSession(Request $request)
    {
        return tap($this->getSession($request), function ($session) use ($request) {
            $session->setRequestOnHandler($request);

            $session->start();
        });
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSession(Request $request)
    {
        return tap($this->sessionManager->driver(), function ($session) use ($request) {
            $sessionId = $this->encrypter->decrypt($request->cookies->get($session->getName()));

            $session->setId($sessionId);
        });
    }
}
