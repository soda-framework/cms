<?php

namespace Themes\SodaExample\Controllers;

use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        return view('soda-example::homepage', compact('request'));
    }
}
