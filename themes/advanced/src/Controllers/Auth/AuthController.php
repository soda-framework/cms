<?php

namespace Themes\SodaTheme\Controllers\Auth;

use Auth;
use Themes\SodaTheme\Models\User;
use App\Http\Controllers\Controller;
use Themes\SodaTheme\Requests\LoginFormRequest;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;
    protected $guard = 'username';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    /**
     * The Main login view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginForm()
    {
        return view('soda_theme_hint::login', compact('request'));
    }

    /**
     * This login method creates a user if the email is not present.
     * You probable want to change the auth guard to something else if
     * you want a real login.
     *
     * @param LoginFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->except(['_token']);
        if (Auth::guard('username')->attempt($credentials)) {
            return redirect()->route('soda_theme_hint.restricted', compact('request'));
        } else {
            //create the user.
            $user = new User();
            $user->email = $request->get('email');
            $user->save();

            Auth::guard('username')->loginUsingId($user->id);

            return redirect()->route('soda_theme_hint.restricted', compact('request'));
        }
    }

    /**
     * logout functions.
     *
     * @param  bool $redirect page to redirect to when complete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout($redirect = false)
    {
        Auth::logout();

        return redirect()->to('/')->with('success', 'Logged out succesfully');
    }
}
