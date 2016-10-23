<?php

namespace Soda\Cms\Http\Middleware;

use Closure;

/*
 *
 * This middleware disables all drafting in the CMS, so drafted entries remain visible
 *
 */

class EnableDrafts
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
        $page = resolve_class('soda.page.model');
        $pageType = resolve_class('soda.page-type.model');

        foreach ([$page, $pageType] as $class) {
            if (method_exists($class, 'disableDrafts')) {
                $class::disableDrafts();
            }
        }

        return $next($request);
    }
}
