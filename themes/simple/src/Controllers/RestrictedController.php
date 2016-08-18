<?php

namespace Themes\SodaTheme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        return view('soda_theme_hint::logged_in', compact('request'));
    }

}
