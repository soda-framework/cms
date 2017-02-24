<?php

namespace Soda\Cms\Database\Support\Interfaces;

use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    /**
     * @param integer $id
     */
    public function findById($id);

    /**
     * @param integer $id
     */
    public function save(Request $request, $id = null);

    /**
     * @param integer $id
     */
    public function destroy($id);

    /**
     * @param Request $attributes
     */
    public function newInstance($attributes);
}
