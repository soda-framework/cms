<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Soda\Cms\Support\Facades\RequestMatcher;
use Soda\Cms\Support\Facades\Session;

class HomeController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function getIndex()
    {
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
     * @param Request $request
     * @param string  $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $slug = '/')
    {
        return RequestMatcher::match($request, $slug);
    }

}
