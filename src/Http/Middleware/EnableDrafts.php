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
        $block = resolve_class('soda.block.model');
        $blockType = resolve_class('soda.block-type.model');
        $page = resolve_class('soda.page.model');
        $pageType = resolve_class('soda.page-type.model');

        foreach ([$block, $blockType, $page, $pageType] as $class) {
            if (method_exists($class, 'disableDrafts')) {
                $class::disableDrafts();
            }
        }

        return $next($request);
    }
}
