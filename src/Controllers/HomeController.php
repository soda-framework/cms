<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Gate;
use Soda\Cms\Events\DashboardWasRendered;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getIndex() {
        if (!Auth::user()->can('access-cms')) {
            return response()->view("soda::errors.no-permission", [], 401);
        }

        $dashboard = event(new DashboardWasRendered);

        if (!empty($dashboard)) {
            return $dashboard[0];
        } else {
            return view('soda::dashboard');
        }
    }

}
