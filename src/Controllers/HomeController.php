<?php namespace Soda\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
//		$this->middleware('soda');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('soda::home.home');
	}

}
