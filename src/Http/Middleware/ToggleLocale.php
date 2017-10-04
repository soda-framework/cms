<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Soda\Cms\Database\Models\Contracts\HasLocale;

class ToggleLocale
{
    public function handle($request, Closure $next)
    {
        if($locale = $request->input('locale')) {
            app()->setLocale($locale);
            $this->rememberLocale($locale ?: '');
        } else if ($user = app('soda')->auth()->user()) {
            if ($user instanceof HasLocale) {
                if($user->locale) app()->setLocale($user->locale);

                if (!$this->hasLocaleCookie($request) || $this->getLocaleCookie($request) != $user->locale) {
                    $this->rememberLocale($user->locale ?: '');
                }
            } elseif ($this->hasLocaleCookie($request)) {
                $this->forgetLocale();
            }
        } elseif ($this->hasLocaleCookie($request)) {
            app()->setLocale($this->getLocaleCookie($request));
        } elseif ($application = app('soda')->getApplication()) {
            if ($application->default_locale) {
                app()->setLocale($application->default_locale);
            }
        }

        return $next($request);
    }

    public function rememberLocale($locale)
    {
        return cookie()->queue(
            cookie()->forever($this->cookieKey(), $locale)
        );
    }

    public function forgetLocale()
    {
        return cookie()->queue(
            cookie()->forget($this->cookieKey())
        );
    }

    public function getLocaleCookie($request)
    {
        return $request->cookie($this->cookieKey());
    }

    public function hasLocaleCookie($request)
    {
        return $request->hasCookie($this->cookieKey());
    }

    public function cookieKey()
    {
        return 'soda_locale';
    }
}


/*

*/
