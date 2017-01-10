<?php

namespace Soda\Cms\Http\Middleware;

use Closure;

/*
 *
 * This middleware disables all drafting in the CMS, so drafted entries remain visible
 *
 */

class Drafting
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
        $draftables = app('soda.drafting')->getDraftables();
        foreach($draftables as $draftable)
        {
            $draftable::disableDrafts();
        }

        return $next($request);
    }
}
