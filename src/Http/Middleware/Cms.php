<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Page;
use Soda\Cms\Models\PageType;

/*
 *
 * This middleware disables all drafting in the CMS, so drafted entries remain visible
 *
 */

class Cms
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
        $draftables = app('soda.page')->getDraftables();
        foreach($draftables as $draftable)
        {
            $draftable::disableDrafts();
        }

        config()->set('auth.defaults.guard', 'soda');

        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Content-Security-Policy', 'default-src * data:; script-src \'self\' \'unsafe-inline\'; style-src * \'unsafe-inline\';'); /*  'unsafe-eval'  */

        return $response;
    }
}
