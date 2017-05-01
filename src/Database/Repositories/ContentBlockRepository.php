<?php

namespace Soda\Cms\Database\Repositories;

use Illuminate\Http\Request;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Models\Contracts\ContentInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentBlockRepositoryInterface;
use Soda\Cms\Support\Facades\Soda;

class ContentBlockRepository implements ContentBlockRepositoryInterface
{
    protected $content;
    protected $blockTypes;

    public function __construct(ContentInterface $content, BlockTypeInterface $blockTypes)
    {
        $this->content = $content;
        $this->blockTypes = $blockTypes;
    }

    public function attach($contentId, $blockTypeId, $contentBlockTypeParams = [])
    {
        $content = $this->content->findOrFail($contentId);
        $content->blockTypes()->attach($blockTypeId, $contentBlockTypeParams);
    }

    public function detach($contentId, $blockTypeId)
    {
        $content = $this->content->findOrFail($contentId);
        $content->blockTypes()->detach($blockTypeId);
    }

    public function findById($contentId, $blockTypeId, $blockId)
    {
        list($content, $blockType) = array_values($this->getParents($contentId, $blockTypeId));

        $block = $blockType->blockQuery($content->id)->where('id', $blockId)->first();

        return compact('content', 'blockType', 'block');
    }

    public function newInstance($contentId, $blockTypeId)
    {
        list($content, $blockType) = array_values($this->getParents($contentId, $blockTypeId));

        $block = Soda::dynamicBlock($blockType->identifier)->newInstance([
            'content_id'        => $content->getKey(),
            'block_type_id'  => $blockType->getKey(),
            'is_shared'      => $blockType->is_shared,
        ]);

        return compact('content', 'blockType', 'block');
    }

    public function save(Request $request, $contentId, $blockTypeId, $blockId = null)
    {
        list($content, $blockType, $block) = array_values($blockId === null ? $this->newInstance($contentId, $blockTypeId) : $this->findById($contentId, $blockTypeId, $blockId));

        if (! $blockType->relationLoaded('fields')) {
            $blockType->load('fields');
        }

        foreach ($blockType->fields as $field) {
            if ($request->input($field->field_name) !== null) {
                $block->parseField($field, $request);
            }
        }

        $block->save();

        return compact('content', 'blockType', 'block');
    }

    public function destroy($contentId, $blockTypeId, $blockId)
    {
        list($content, $blockType, $block) = array_values($this->findById($contentId, $blockTypeId, $blockId));

        $block->delete();

        return compact('content', 'blockType', 'block');
    }

    /**
     * @param int $contentId
     * @param int $blockTypeId
     *
     * @return array
     */
    protected function getParents($contentId, $blockTypeId)
    {
        $content = $this->content->findOrFail($contentId);
        $blockType = $this->blockTypes->findOrFail($blockTypeId);

        return compact('content', 'blockType');
    }
}
