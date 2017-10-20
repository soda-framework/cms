<?php

namespace Soda\Cms\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeFieldRepositoryInterface;

class BlockTypeFieldController extends BaseController
{
    protected $blockTypeFields;

    public function __construct(BlockTypeFieldRepositoryInterface $blockTypeFields)
    {
        $this->blockTypeFields = $blockTypeFields;
    }

    /**
     * Attaches a new resource.
     *
     * @param Request $request
     * @param  int    $blockTypeId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attach(Request $request, $blockTypeId)
    {
        try {
            $this->blockTypeFields->attach($blockTypeId, $request->input('fieldable_id'), $request->only('show_in_table'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.attach', ['object' => trans('soda::terminology.field')]));
        }

        return redirect()->route('soda.block-types.edit', [$blockTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.attached', ['object' => trans('soda::terminology.field')]));
    }

    /**
     * Detaches an existing resource.
     *
     * @param  int $blockTypeId
     * @param  int $fieldId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detach($blockTypeId, $fieldId)
    {
        try {
            $this->blockTypeFields->detach($blockTypeId, $fieldId);
        } catch (Exception $e) {
            return $this->handleException($e, trans('soda::errors.detach', ['object' => trans('soda::terminology.field')]));
        }

        return redirect()->route('soda.block-types.edit', [$blockTypeId, 'tab' => 'fields'])->with('success', trans('soda::messages.detached', ['object' => trans('soda::terminology.field')]));
    }
}
