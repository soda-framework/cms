<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Application\Interfaces\ApplicationRepositoryInterface;

class ApplicationController extends BaseController
{
    protected $applications;

    public function __construct(ApplicationRepositoryInterface $applications)
    {
        $this->applications = $applications;

        $this->middleware('soda.permission:view-application-settings')->only(['edit']);
        $this->middleware('soda.permission:edit-application-settings')->only(['update']);
        //$this->middleware('soda.permission:create-applications')->only(['create', 'store']);
        //$this->middleware('soda.permission:delete-applications')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return soda_cms_view('data.application.index', $this->applications->getFilteredGrid(10));
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        $application = $this->applications->getApplication($id);

        return soda_cms_view('data.application.view', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        try {
            $application = $this->applications->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'application']));
        }

        return redirect()->route('soda.application.edit', $application->getKey())->with('success', trans('soda::messages.updated', ['object' => 'application']));
    }
}
