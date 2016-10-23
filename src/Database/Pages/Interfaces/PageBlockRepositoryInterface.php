<?php
namespace Soda\Cms\Database\Pages\Interfaces;

use Illuminate\Http\Request;

interface PageBlockRepositoryInterface
{
    public function attach($pageId, $blockId, $pageBlockParams = []);

    public function detach($pageId, $blockId);

    public function findById($pageId, $blockId, $modelId);

    public function newInstance($pageId, $blockId);

    public function save(Request $request, $pageId, $blockId, $modelId = null);

    public function destroy($pageId, $blockId, $modelId);
}
