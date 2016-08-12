<?php

namespace Themes\SodaTheme\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class RestrictedController extends Controller {

    public function __construct() {
        $this->middleware(\Themes\SodaTheme\Middleware\Authenticate::class.':username');
    }

    public function index(Request $request) {
        return view('soda_theme_hint::logged_in', compact('request'));
    }

}
