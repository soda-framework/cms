<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Soda\Cms\Database\Models\Quicklink;

class HomeController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return soda_cms_view('dashboard');
    }

    public function toggleDraft()
    {
        $draftMode = Session::get('soda.draft_mode') == true ? false : true;

        Session::put('soda.draft_mode', $draftMode);

        return redirect()->back()->with('info', ($draftMode ? 'Draft' : 'Live').' mode active. <a href="/" target="_blank">View site</a>');
    }

    public function resetWeakPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $request->user()->password = \Hash::make($request->input('password'));
        $request->user()->save();

        return redirect()->back()->with('success', 'Password reset successfully');
    }

    public function addQuicklink(Request $request)
    {
        $this->validate($request, ['route_name' => 'required', 'text' => 'required']);

        $quicklink = Quicklink::firstOrNew([
            'user_id'        => $request->user()->id,
            'text'           => $request->input('text'),
            'route_name'     => $request->input('route_name'),
            'route_params'   => $request->input('route_params'),
            'request_params' => $request->input('request_params'),
        ])->fill([
            'route_params'   => json_decode($request->input('route_params')),
            'request_params' => json_decode($request->input('request_params')),
        ]);

        $quicklink->save();

        return redirect()->to($quicklink->getUrl())->with('success', 'Quicklink added.');
    }
}
