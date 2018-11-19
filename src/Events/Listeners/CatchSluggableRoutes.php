<?php

namespace Soda\Cms\Events\Listeners;

use Illuminate\Http\Request;
use Illuminate\Routing\RouteAction;
use Illuminate\Session\SessionManager;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\Middleware\EncryptCookies;

class CatchSluggableRoutes
{
    protected $sessionManager;
    protected $encrypter;

    /**
     * Create a new session middleware.
     *
     * @param  \Illuminate\Session\SessionManager         $sessionManager
     * @param  \Illuminate\Contracts\Encryption\Encrypter $encrypter
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
                $this->startSession($event->request)
            );

            $action = app('soda.request-matcher')->match($event->request);

            $event->route->setAction(array_merge(RouteAction::parse($event->route->uri, $action), ['middleware' => 'soda.sluggable-web']));
        }
    }

    /**
     * Start the session for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     *
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSession(Request $request)
    {
        return tap($this->sessionManager->driver(), function ($session) use ($request) {
            $isSerialized = EncryptCookies::serialized($session->getName());

            if ($encryptedSessionId = $request->cookies->get($session->getName())) {
                $sessionId = $this->encrypter->decrypt($encryptedSessionId, $isSerialized);
                $session->setId($sessionId);
            }
        });
    }
}
