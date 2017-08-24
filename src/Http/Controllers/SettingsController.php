<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ApplicationRepositoryInterface;

class SettingsController extends BaseController
{
    protected $applications;

    public function __construct(ApplicationRepositoryInterface $applications)
    {
        $this->applications = $applications;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading(ucwords(trans('soda::terminology.settings')))->setHeadingIcon('mdi mdi-settings');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:view-application-settings')->only(['edit']);
        $this->middleware('soda.permission:edit-application-settings')->only(['update']);
        //$this->middleware('soda.permission:create-applications')->only(['create', 'store']);
        //$this->middleware('soda.permission:delete-applications')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return soda_cms_view('data.settings.index', $this->applications->getFilteredGrid(10));
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id = null)
    {
        $application = $this->applications->getApplication($id);
        $settings = $this->applications->getSettingsForApplication($application);

        if ($settings !== null) {
            $settingsByCategory = $settings->transform(function ($item) {
                if (! $item->category) {
                    $item->category = 'Settings';
                }

                return $item;
            })->groupBy('category');

            // Move 'Settings' category to the start
            if (isset($settingsByCategory['Settings'])) {
                $defaultCategory = $settingsByCategory->pull('Settings');

                $settingsByCategory->prepend($defaultCategory, 'Settings');
            }
        }

        return soda_cms_view('data.settings.view', compact('application', 'settingsByCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id = null)
    {
        try {
            $application = $this->applications->save($request, $id);
            if ($request->has('theme')) {
                \Cookie::queue('soda-theme', $request->input('theme'));
                $request->session()->put('soda-theme', $request->input('theme'));
            }
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'application']));
        }

        return redirect()->route('soda.settings.edit', $application->getKey())->with('success', trans('soda::messages.updated', ['object' => 'application']));
    }
}
