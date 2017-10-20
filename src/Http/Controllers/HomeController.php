<?php

namespace Soda\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Soda\Cms\Database\Models\Contracts\HasLocale;
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

        return redirect()->back()->with('info', trans('soda::messages.mode_active', ['mode' => $draftMode ? trans('soda::misc.draft_mode') : trans('soda::misc.live_mode')]));
    }

    public function resetWeakPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $request->user()->password = \Hash::make($request->input('password'));
        $request->user()->save();

        return redirect()->back()->with('success', trans('soda::messages.password_reset'));
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

        return redirect()->to($quicklink->getUrl())->with('success', trans('soda::messages.created', ['object' => trans('soda::terminology.quicklink')]));
    }

    public function setLanguage(Request $request)
    {
        $user = app('soda')->auth()->user();
        $locale = $request->input('language');

        if ($user && $user instanceof HasLocale) {
            $user->locale = $locale;
            $user->save();
        } else {
            cookie()->queue(cookie()->forever('soda_locale', $locale));
        }

        return redirect()->back()->with('success', trans('soda::messages.language_updated', [], $locale));
    }

    public function switchApplication(Request $request)
    {
        $parsedUrl = parse_url($request->input('redirect'));
        $queryString = [];

        $validationToken = md5(uniqid(rand(), true));

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryString);
        }

        if (! isset($parsedUrl['scheme'])) {
            $parsedUrl['scheme'] = 'http';
        }

        if (! isset($parsedUrl['path'])) {
            $parsedUrl['path'] = '/';
        }

        $queryString['sid'] = $request->session()->getId();
        $queryString['validate'] = $validationToken;
        $parsedUrl['query'] = http_build_query($queryString);

        $request->session()->flash('redirect-validate', $validationToken);

        return redirect()->away($parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . $parsedUrl['query']);
    }
}
