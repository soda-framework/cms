<?php

namespace Themes\SodaTheme\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('soda_theme_hint::homepage', compact('request'));
    }
}
