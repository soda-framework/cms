<?php

namespace Soda\Cms\Http\Controllers;

use Auth;
use Gate;
use Request;
use Session;
use SodaMatcher;
use SodaMenu;

class HomeController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getIndex()
    {
        if (!Auth::user()->can('access-cms')) {
            return response()->view(soda_cms_view_path("errors.no-permission"), [], 401);
        }

        return soda_cms_view('dashboard');
    }

    public function getToggleDraft()
    {
        $draft_mode = Session::get("soda.draft_mode") == true ? false : true;

        Session::set("soda.draft_mode", $draft_mode);

        return redirect()->back()->with("info", ($draft_mode ? "Draft" : "Live")." mode active. <a href=\"/\" target=\"_blank\">View site</a>");
    }

    /**
     * Main page view method.
     *
     * @param $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page($slug = '/')
    {
        return SodaMatcher::match($slug)->render();
    }

}
