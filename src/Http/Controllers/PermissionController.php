<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Soda\Cms\Database\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionController extends BaseController
{
    protected $permissions;

    public function __construct(PermissionRepositoryInterface $permissions)
    {
        $this->permissions = $permissions;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading('Permissions')->setHeadingIcon('mdi mdi-key-variant');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:view-permissions');
        $this->middleware('soda.permission:create-permissions')->only(['create', 'store']);
        $this->middleware('soda.permission:edit-permissions')->only(['edit', 'update']);
        $this->middleware('soda.permission:delete-permissions')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return soda_cms_view('data.permissions.index', $this->permissions->getFilteredGrid(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        app('soda.interface')->setHeading('New Permission');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.permissions.index'), 'Permissions');

        try {
            $permission = $this->permissions->newInstance($request);
            $roleIds = $this->permissions->getRoles();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'permission']));
        }

        return soda_cms_view('data.permissions.view', compact('permission', 'roleIds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        try {
            $permission = $this->permissions->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'permission']));
        }

        return redirect()->route('soda.permissions.edit', $permission->getKey())->with('success', trans('soda::messages.created', ['object' => 'permission']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id)
    {
        $permission = $this->permissions->findById($id);
        $roleIds = $this->permissions->getRoles();

        if (! $permission) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'permission']));
        }

        app('soda.interface')->setHeading($permission->display_name);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.permissions.index'), 'Permissions');

        return soda_cms_view('data.permissions.view', compact('permission', 'roleIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $permission = $this->permissions->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'permission']));
        }

        return redirect()->route('soda.permissions.edit', $permission->getKey())->with('success', trans('soda::messages.updated', ['object' => 'permission']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        try {
            $this->permissions->destroy($id);
        } catch (ModelNotFoundException $e) {
            return $this->handleException($e, trans('soda::errors.not-found', ['object' => 'permission']));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'permission']));
        }

        return redirect()->route('soda.permissions.index')->with('warning', trans('soda::messages.deleted', ['object' => 'permission']));
    }
}
