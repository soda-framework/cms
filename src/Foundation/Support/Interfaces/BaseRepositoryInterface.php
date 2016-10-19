<?php

namespace Soda\Cms\Foundation\Support\Interfaces;

use Illuminate\Http\Request;

interface BaseRepositoryInterface {
    public function findById($id);
    public function save(Request $request, $id = null);
    public function destroy($id);
    public function newInstance($attributes);
}
