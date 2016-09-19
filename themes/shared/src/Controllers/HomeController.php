<?php

namespace Themes\SodaExample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('soda-example::homepage', compact('request'));
    }

}
