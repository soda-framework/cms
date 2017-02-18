<?php

namespace Soda\Cms\Http\Controllers;

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
        $draft_mode = Session::get('soda.draft_mode') == true ? false : true;

        Session::set('soda.draft_mode', $draft_mode);

        return redirect()->back()->with('info', ($draft_mode ? 'Draft' : 'Live').' mode active. <a href="/" target="_blank">View site</a>');
    }
}
