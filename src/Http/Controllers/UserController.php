<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Soda\Cms\Database\Repositories\Contracts\UserRepositoryInterface;

class UserController extends BaseController
{
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;

        $this->middleware(function ($request, $next) {
            app('soda.interface')->setHeading('Users')->setHeadingIcon('mdi mdi-account-circle');
            app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), ucfirst(trans('soda::terminology.home')));

            return $next($request);
        });

        $this->middleware('soda.permission:view-users');
        $this->middleware('soda.permission:create-users')->only(['create', 'store']);
        $this->middleware('soda.permission:edit-users')->only(['edit', 'update']);
        $this->middleware('soda.permission:delete-users')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return soda_cms_view('data.users.index', $this->users->getFilteredGrid(10));
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
        app('soda.interface')->setHeading('New User');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.users.index'), 'Users');

        try {
            $user = $this->users->newInstance($request);
            $roleIds = $this->users->getRoles();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'user']));
        }

        return soda_cms_view('data.users.view', compact('user', 'roleIds'));
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
        $this->validate($request, [
            'password' => 'confirmed',
        ]);

        try {
            $user = $this->users->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'user']));
        }

        return redirect()->route('soda.users.edit', $user->getKey())->with('success', trans('soda::messages.created', ['object' => 'user']));
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
        $user = $this->users->findById($id);
        $roleIds = $this->users->getRoles();

        if (! $user) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'user']));
        }

        app('soda.interface')->setHeading($user->username);
        app('soda.interface')->breadcrumbs()->addLink(route('soda.users.index'), 'Users');

        return soda_cms_view('data.users.view', compact('user', 'roleIds'));
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
        $this->validate($request, [
            'password' => 'sometimes|confirmed',
        ]);

        try {
            $user = $this->users->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'user']));
        }

        return redirect()->route('soda.users.edit', $user->getKey())->with('success', trans('soda::messages.updated', ['object' => 'user']));
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
            $this->users->destroy($id);
        } catch (ModelNotFoundException $e) {
            return $this->handleException($e, trans('soda::errors.not-found', ['object' => 'user']));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'user']));
        }

        return redirect()->route('soda.users.index')->with('warning', trans('soda::messages.deleted', ['object' => 'user']));
    }
}
