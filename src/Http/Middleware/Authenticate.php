<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Soda\Cms\Support\Facades\Soda;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;

class Authenticate extends BaseAuthenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    protected $hasher;
    protected $unsafePasswords = [
        'password',
        'admin',
        'letmein',
        'qwerty',
    ];

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory|Auth $auth
     * @param Hasher                                  $hasher
     */
    public function __construct(Auth $auth, Hasher $hasher)
    {
        $this->auth = $auth;
        $this->hasher = $hasher;
    }

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

            if (env('APP_ENV') == 'production' && $request->route()->getName() !== 'soda.reset-weak-password') {
                foreach ($this->unsafePasswords as $unsafePassword) {
                    if ($this->hasher->check($unsafePassword, $request->user()->getAuthPassword())) {
                        return Soda::resetPassword();
                    }
                }
            }
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
