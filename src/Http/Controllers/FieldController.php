<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Soda\Cms\Database\Repositories\Contracts\FieldRepositoryInterface;

class FieldController extends BaseController
{
    protected $fields;

    public function __construct(FieldRepositoryInterface $fields)
    {
        $this->fields = $fields;

        $this->middleware('soda.permission:manage-fields');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return soda_cms_view('data.fields.index', $this->fields->getFilteredGrid(10));
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
        try {
            $field = $this->fields->newInstance($request);
            $fieldTypes = $this->fields->getFieldTypes();
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'field']));
        }

        return soda_cms_view('data.fields.view', compact('field', 'fieldTypes'));
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
            $field = $this->fields->save($request);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.create', ['object' => 'field']));
        }

        return redirect()->route('soda.fields.edit', $field->getKey())->with('success', trans('soda::messages.created', ['object' => 'field']));
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
        $field = $this->fields->findById($id);
        $fieldTypes = $this->fields->getFieldTypes();

        if (! $field) {
            return $this->handleError(trans('soda::errors.not-found', ['object' => 'field']));
        }

        return soda_cms_view('data.fields.view', compact('field', 'fieldTypes'));
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
            $field = $this->fields->save($request, $id);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.update', ['object' => 'field']));
        }

        return redirect()->route('soda.fields.edit', $field->getKey())->with('success', trans('soda::messages.updated', ['object' => 'field']));
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
            $this->fields->destroy($id);
        } catch (ModelNotFoundException $e) {
            return $this->handleException($e, trans('soda::errors.not-found', ['object' => 'field']));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.delete', ['object' => 'field']));
        }

        return redirect()->route('soda.fields.index')->with('warning', trans('soda::messages.deleted', ['object' => 'field']));
    }
}
