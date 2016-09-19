<?php

namespace Themes\SodaExample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestrictedController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('soda-example::logged_in', compact('request'));
    }

}
