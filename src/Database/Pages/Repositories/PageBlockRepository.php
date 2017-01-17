<?php

namespace Soda\Cms\Database\Pages\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Support\Facades\Soda;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Pages\Interfaces\PageBlockRepositoryInterface;

class PageBlockRepository implements PageBlockRepositoryInterface
{
    protected $pages;
    protected $blockTypes;

    public function __construct(PageInterface $pages, BlockTypeInterface $blockTypes)
    {
        $this->pages = $pages;
        $this->blockTypes = $blockTypes;
    }

    public function attach($pageId, $blockTypeId, $pageBlockTypeParams = [])
    {
        $page = $this->pages->findOrFail($pageId);
        $page->block_types()->attach($blockTypeId, $pageBlockTypeParams);
    }

    public function detach($pageId, $blockTypeId)
    {
        $page = $this->pages->findOrFail($pageId);
        $page->block_types()->detach($blockTypeId);
    }

    public function findById($pageId, $blockTypeId, $blockId)
    {
        list($page, $blockType) = array_values($this->getParents($pageId, $blockTypeId));

        $block = $blockType->blockQuery($page->id)->where('id', $blockId)->first();

        return compact('page', 'blockType', 'block');
    }

    public function newInstance($pageId, $blockTypeId)
    {
        list($page, $blockType) = array_values($this->getParents($pageId, $blockTypeId));

        $block = Soda::dynamicBlock($blockType->identifier)->newInstance([
            'page_id'   => $page->getKey(),
            'block_type_id'  => $blockType->getKey(),
            'is_shared' => $blockType->is_shared,
        ]);

        return compact('page', 'blockType', 'block');
    }

    public function save(Request $request, $pageId, $blockTypeId, $blockId = null)
    {
        list($page, $blockType, $block) = array_values($blockId === null ? $this->newInstance($pageId, $blockTypeId) : $this->findById($pageId, $blockTypeId, $blockId));

        if (! $blockType->relationLoaded('fields')) {
            $blockType->load('fields');
        }

        foreach ($blockType->fields as $field) {
            if ($request->input($field->field_name) !== null) {
                $block->parseField($field, $request);
            }
        }

        $block->save();

        return compact('page', 'blockType', 'block');
    }

    public function destroy($pageId, $blockTypeId, $blockId)
    {
        list($page, $blockType, $block) = array_values($this->findById($pageId, $blockTypeId, $blockId));

        $block->delete();

        return compact('page', 'blockType', 'block');
    }

    protected function getParents($pageId, $blockTypeId)
    {
        $page = $this->pages->findOrFail($pageId);
        $blockType = $this->blockTypes->findOrFail($blockTypeId);

        return compact('page', 'blockType');
    }
}
