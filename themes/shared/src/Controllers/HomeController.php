<?php

namespace Themes\SodaExample\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('soda-example::homepage', compact('request'));
    }
}
