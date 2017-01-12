<?php

namespace Soda\Cms\Http\Middleware;

use Closure;

class Security
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Content-Security-Policy', 'default-src * data:; script-src \'self\' www.google-analytics.com ajax.googleapis.com \'unsafe-inline\'; style-src * \'unsafe-inline\';'); /*  'unsafe-eval'  */

        return $response;
    }
}
