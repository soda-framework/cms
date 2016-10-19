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

        $block = resolve_class(BlockInterface::class);
        $blockType = resolve_class(BlockTypeInterface::class);
        $page = resolve_class(PageInterface::class);
        $pageType = resolve_class(PageTypeInterface::class);

        foreach([$block, $blockType, $page, $pageType] as $class) {
            if(method_exists($class, 'disableDrafts')) {
                $class::disableDrafts();
            }
        }

        return $next($request);
    }
}
