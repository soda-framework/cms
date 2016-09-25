<?php

namespace Themes\SodaExample\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    /**
     * The Main login view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginForm()
    {
        return view('soda-example::login', compact('request'));
    }

    /**
     * This login method creates a user if the email is not present.
     * You probable want to change the auth guard to something else if
     * you want a real login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->except(['_token']);
        if (Auth::attempt($credentials)) {
            return redirect()->route('soda-example.restricted', compact('request'));
        } else {
            //create the user.
            $user = new User();
            $user->email = $request->get('email');
            $user->save();

            Auth::loginUsingId($user->id);

            return redirect()->route('soda-example.restricted', compact('request'));
        }
    }

    /**
     * logout functions
     *
     * @param  boolean $redirect page to redirect to when complete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout($redirect = false)
    {
        Auth::logout();

        return redirect()->to('/')->with('success', 'Logged out succesfully');
    }

}
