<?php

namespace Soda\Cms\Http\Middleware;

use Closure;

class TransferSession
{
    public function handle($request, Closure $next)
    {
        if($request->has('sid') && $request->has('validate')) {
            $oldSessionId = $request->session()->getId();

            $request->session()->setId($request->input('sid'));
            $request->session()->start();

            if($request->session()->get('redirect-validate') === $request->input('validate')) {
                $currentQuery = parse_url($request->fullUrl(), PHP_URL_QUERY);
                parse_str($currentQuery, $currentQueryArray);

                unset($currentQueryArray['sid'], $currentQueryArray['validate']);

                $currentQuery = http_build_query($currentQueryArray);

                return redirect()->to($request->url() . (count($currentQueryArray) ? "?$currentQuery" : ''));
            }

            $request->session()->setId($oldSessionId);
            $request->session()->start();
        }

        return $next($request);
    }
}
