<?php

namespace Themes\SodaExample\Http\Controllers;

use Illuminate\Http\Request;

class RestrictedController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:soda-example');
    }

    public function index(Request $request)
    {
        return view('soda-example::logged-in', compact('request'));
    }

}
