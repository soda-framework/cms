<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Soda\Cms\Database\Users\Interfaces\UserRepositoryInterface;

class UserController extends BaseController
{
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;

        $this->middleware('soda.permission:view-users');
        $this->middleware('soda.permission:create-users')->only(['create', 'store']);
        $this->middleware('soda.permission:edit-users')->only(['edit', 'update']);
        $this->middleware('soda.permission:delete-users')->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->users->findById($id);
        $roleIds = $this->users->getRoles();

        if (! $user) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'user']));
        }

        return soda_cms_view('data.users.view', compact('user', 'roleIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
