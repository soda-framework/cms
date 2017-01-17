<?php

namespace Themes\SodaExample\Controllers;

use Illuminate\Http\Request;

class RestrictedController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('soda-example::logged-in', compact('request'));
    }
}
