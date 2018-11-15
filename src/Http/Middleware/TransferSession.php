<?php

namespace Soda\Cms\Http\Middleware;

use Closure;

class TransferSession
{
    public function handle($request, Closure $next)
    {
        if ($request->has('sid') && $request->has('vtoken')) {
            $sessionId = $request->input('sid');
            $validationToken = $request->input('vtoken');

            if ($this->validateSession($request, $sessionId, $validationToken)) {
                $this->switchSession($request, $sessionId);

                return redirect()->to($this->removeSessionQueryParameters($request));
            }
        }

        return $next($request);
    }

    protected function validateSession($request, $sessionId, $validationToken)
    {
        $sessionData = $this->parseSessionById($request, $sessionId);

        if (is_array($sessionData) && isset($sessionData['vtoken']) && $sessionData['vtoken'] == $validationToken) {
            return true;
        }

        return false;
    }

    protected function parseSessionById($request, $sessionId)
    {
        return @unserialize($request->session()->getHandler()->read($sessionId));
    }

    protected function switchSession($request, $sessionId)
    {
        $request->session()->setId($sessionId);
        $request->session()->start();
    }

    protected function removeSessionQueryParameters($request)
    {
        $currentQuery = parse_url($request->fullUrl(), PHP_URL_QUERY);
        parse_str($currentQuery, $currentQueryArray);

        unset($currentQueryArray['sid'], $currentQueryArray['vtoken']);

        $currentQuery = http_build_query($currentQueryArray);

        return $request->url().(count($currentQueryArray) ? "?$currentQuery" : '');
    }
}
