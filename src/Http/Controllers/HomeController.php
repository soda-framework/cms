<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Support\Facades\Session;

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
        $draftMode = Session::get('soda.draft_mode') == true ? false : true;

        Session::put('soda.draft_mode', $draftMode);

        return redirect()->back()->with('info', ($draftMode ? 'Draft' : 'Live').' mode active. <a href="/" target="_blank">View site</a>');
    }
}
