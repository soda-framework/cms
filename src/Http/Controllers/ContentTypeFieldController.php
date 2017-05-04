<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeFieldRepositoryInterface;

class ContentTypeFieldController extends BaseController
{
    protected $contentTypeFields;

    public function __construct(ContentTypeFieldRepositoryInterface $contentTypeFields)
    {
        $this->content = $contentTypeFields;
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $contentTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $contentTypeId)
    {
        try {
            $this->content->attach($contentTypeId, $request->input('fieldable_id'), $request->only('show_in_table'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => trans('soda::terminology.field')]));
        }

        return redirect()->route('soda.content-types.edit', [$contentTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.attached', ['object' => trans('soda::terminology.field')]));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int    $contentTypeId
     * @param  int    $fieldId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($contentTypeId, $fieldId)
    {
        try {
            $this->content->detach($contentTypeId, $fieldId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => trans('soda::terminology.field')]));
        }

        return redirect()->route('soda.content-types.edit', [$contentTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.detached', ['object' => trans('soda::terminology.field')]));
    }
}
