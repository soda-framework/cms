<?php

namespace Themes\SodaTheme\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

	public function __construct() {
		//$this->middleware(\Themes\SodaTheme\Middleware\Authenticate::class.':username');
	}

	public function index(Request $request) {
		return view('soda_theme_hint::homepage', compact('request'));
	}

}
