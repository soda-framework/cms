<?php

namespace Soda\Cms\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Soda\Cms\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('soda.guest');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route('soda.home');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('soda');
    }
}
