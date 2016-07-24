<?php

namespace Soda\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class Authenticate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		config()->set('auth.defaults.guard', $guard); //test me!
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