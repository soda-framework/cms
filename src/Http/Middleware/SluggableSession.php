<?php

namespace Soda\Cms\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

class SluggableSession extends StartSession
{
    /**
     * Start the session for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function startSession(Request $request)
    {
        return tap($this->getSession($request), function($session) use ($request) {
            if (!$session->isStarted()) {
                $session->setRequestOnHandler($request);

                $session->start();
            }
        });
    }
}
