<?php

namespace Soda\Cms\Http\Middleware;

/*
 * This file is part of Laratrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Laratrust
 */

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Soda\Cms\Support\Facades\SodaFacade as Soda;

class HasRole
{
    const DELIMITER = '|';

    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @param                           $roles
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (! is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if ($this->auth->guest() || ! $request->user()->hasRole($roles)) {
            return Soda::noPermission();
        }

        return $next($request);
    }
}
