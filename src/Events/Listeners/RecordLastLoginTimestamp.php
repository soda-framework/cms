<?php

namespace Soda\Cms\Events\Listeners;

use Illuminate\Auth\Events\Login;

class RecordLastLoginTimestamp
{
    /**
     * Handle the event.
     *
     * @param  Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        if (method_exists($user, 'updateLoginTimestamp')) {
            $user->updateLoginTimestamp();
        }
    }
}
