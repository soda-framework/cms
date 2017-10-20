<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        config()->set('auth.defaults.guard', 'soda');

        if (Auth::check()) {
            return redirect()->route('soda.home');
        }

        return $next($request);
    }
}
