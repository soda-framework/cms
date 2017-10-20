<?php

namespace Soda\Cms\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class DraftAlert
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
        $response = $next($request);

        if ($request->session()->get('soda.draft_mode') == true && ! $request->is(config('soda.cms.path').'*')) {
            $sodaUser = \Auth::guard('soda')->user();
            if ($sodaUser && $sodaUser->can('view-drafts')) {
                $this->injectAlert($response);
            }
        }

        return $response;
    }

    /**
     * Injects the alertbar into the given Response.
     *
     * @param Response $response A Response instance
     *                           Based on
     *                           https://github.com/symfony/WebProfilerBundle/blob/master/EventListener/WebDebugToolbarListener.php
     */
    protected function injectAlert(Response $response)
    {
        $content = $response->getContent();

        $pos = strripos($content, '<body>');
        if (false !== $pos) {
            $renderedContent = soda_cms_view('partials.draft-alert')->render();

            $content = substr($content, 0, $pos).$renderedContent.substr($content, $pos);

            // Update the new content and reset the content length
            $response->setContent($content);
            $response->headers->remove('Content-Length');
        }
    }
}
