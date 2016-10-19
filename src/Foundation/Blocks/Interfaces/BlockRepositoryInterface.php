<?php
namespace Soda\Cms\Foundation\Blocks\Interfaces;

use Illuminate\Http\Request;

interface BlockRepositoryInterface
{
    public function findById($id);

    public function getBlockTypes();

    public function getFilteredBlockGrid($perPage);

    public function createStub($blockTypeId = null);

    public function save(Request $request, $id = null);

    public function destroy($id);
}
