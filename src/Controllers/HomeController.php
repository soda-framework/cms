<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Gate;
use Request;
use Session;
use Soda\Cms\Events\DashboardWasRendered;
use SodaMenu;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getIndex()
    {
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

    public function getToggleDraft()
    {
        $draft_mode = Session::get("soda.draft_mode") == true ? false : true;

        Session::set("soda.draft_mode", $draft_mode);

        return redirect()->back()->with("info", ($draft_mode ? "Draft" : "Live")." mode active. <a href=\"/\" target=\"_blank\">View site</a>");
    }

    public function getTest()
    {

        return (SodaMenu::render('sidebar'));
    }

}
