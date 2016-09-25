<?php

namespace Themes\SodaExample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Themes\SodaExample\Middleware\Authenticate;

class RestrictedController extends Controller
{

    public function __construct()
    {
        $this->middleware(Authenticate::class.':username');
    }

    public function index(Request $request)
    {
        return view('soda-example::logged_in', compact('request'));
    }

}
