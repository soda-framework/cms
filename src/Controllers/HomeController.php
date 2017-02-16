<?php

namespace Soda\Controllers;

use Gate;
use App\Http\Controllers\Controller;
use Soda\Events\DashboardWasRendered;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
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
        if (Gate::denies('soda.dashboard')) {
            dd('nope');
            abort(403);
        }

        $dashboard = event(new DashboardWasRendered());

        if (! empty($dashboard)) {
            return $dashboard[0];
        } else {
            return view('soda::home.dashboard');
        }
    }
}
