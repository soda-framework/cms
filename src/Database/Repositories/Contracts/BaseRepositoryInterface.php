<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    /**
     * @param int $id
     */
    public function findById($id);

    /**
     * @param int $id
     */
    public function save(Request $request, $id = null);

    /**
     * @param int $id
     */
    public function destroy($id);

    /**
     * @param Request $attributes
     */
    public function newInstance($attributes);
}
