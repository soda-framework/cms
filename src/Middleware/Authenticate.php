<?php

namespace Soda\Cms\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //this is a work around for a laravel bug - the guard flicks back to the default when run through an auth Gate
        //so we need to temporarily set the guard to the incomming guard here instead.
        config()->set('auth.defaults.guard', $guard);
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
