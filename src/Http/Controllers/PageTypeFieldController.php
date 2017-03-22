<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\PageTypeFieldRepositoryInterface;

class PageTypeFieldController extends BaseController
{
    protected $pageTypeFields;

    public function __construct(PageTypeFieldRepositoryInterface $pageTypeFields)
    {
        $this->pageTypeFields = $pageTypeFields;
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $pageTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $pageTypeId)
    {
        try {
            $this->pageTypeFields->attach($pageTypeId, $request->input('fieldable_id'), $request->only('show_in_table'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => 'field']));
        }

        return redirect()->route('soda.page-types.edit', [$pageTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.attached', ['object' => 'field']));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int    $pageTypeId
     * @param  int    $fieldId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($pageTypeId, $fieldId)
    {
        try {
            $this->pageTypeFields->detach($pageTypeId, $fieldId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => 'field']));
        }

        return redirect()->route('soda.page-types.edit', [$pageTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.detached', ['object' => 'field']));
    }
}
