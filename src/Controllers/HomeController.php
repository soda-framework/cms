<?php namespace Soda\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
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
		//dd(Auth::guard('soda')->user(), \Gate::denies('soda.dashboard'), \Gate::forUser(Auth::guard('soda')->user()));

		//dd(\Gate::forUser(Auth::guard('soda')->user())->denies('soda.dashboard'));
		//dd(Auth::guard('soda')->user(), Auth::user());
		if (\Gate::denies('soda.dashboard')) {
			dd('nope');
			abort(403);

		}
		$dashboard = event(new \Soda\Events\DashboardWasRendered());
		if(!empty($dashboard)){
			return $dashboard[0];
		}
		else{
			return view('soda::home.dashboard');
		}
		//dd(event(new \Soda\Events\DashboardWasRendered()));
		//return event(new Soda\Events\DashboardWasRendered());



	}

}
