<?php

namespace Soda\Cms\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Soda\Cms\Models\User;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectPath;
    protected $redirectAfterLogout;
    protected $loginPath;

    protected $guard = 'soda';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->redirectPath = route('soda.home');
        $this->redirectAfterLogout = route('soda.home');
        $this->loginPath = route('soda.login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin()
    {
        if (view()->exists('soda::auth.authenticate')) {
            return view('soda::auth.authenticate');
        }

        return view('soda::auth.login');
    }

    public function getRegister()
    {
        return view('soda::auth.register');
    }

    public function postLogin(Request $request)
    {
        config()->set('auth.defaults.guard', $this->guard);

        return $this->login($request);
    }
}
