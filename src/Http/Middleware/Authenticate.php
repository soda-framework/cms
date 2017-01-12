<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;

class Authenticate extends BaseAuthenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @param array                     $guards
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        config()->set('auth.defaults.guard', 'soda');

        try {
            $this->authenticate(['soda']);
        } catch (AuthenticationException $e) {
            return $this->unauthenticated($request, $e);
        }

        return $next($request);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request                 $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('soda.login');
    }
}
