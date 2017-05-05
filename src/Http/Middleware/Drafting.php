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
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $draftables = app('soda.drafting')->getDraftables();

        foreach ($draftables as $draftable) {
            $draftable::disableDrafts();
        }

        return $next($request);
    }
}
