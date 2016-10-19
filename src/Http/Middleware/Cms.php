<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;

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
        config()->set('auth.defaults.guard', 'soda');

        $block = resolve_class('soda.block.model');
        $blockType = resolve_class('soda.block-type.model');
        $page = resolve_class('soda.page.model');
        $pageType = resolve_class('soda.page-type.model');

        foreach([$block, $blockType, $page, $pageType] as $class) {
            if(method_exists($class, 'disableDrafts')) {
                $class::disableDrafts();
            }
        }

        return $next($request);
    }
}
