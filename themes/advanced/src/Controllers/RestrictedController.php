<?php

namespace Themes\SodaTheme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Themes\SodaTheme\Middleware\Authenticate;

class RestrictedController extends Controller {

    public function __construct() {
        $this->middleware(Authenticate::class . ':username');
    }

    public function index(Request $request) {
        return view('soda_theme_hint::logged_in', compact('request'));
    }

}
