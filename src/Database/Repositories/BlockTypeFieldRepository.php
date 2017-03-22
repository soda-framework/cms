<?php

namespace Soda\Cms\Database\Repositories;

use Soda\Cms\Database\Models\Contracts\FieldInterface;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\BlockTypeFieldRepositoryInterface;

class BlockTypeFieldRepository implements BlockTypeFieldRepositoryInterface
{
    protected $blockTypes;
    protected $fields;

    public function __construct(BlockTypeInterface $blockTypes, FieldInterface $fields)
    {
        $this->blockTypes = $blockTypes;
        $this->fields = $fields;
    }

    public function attach($blockTypeId, $fieldId, $blockTypeFieldParams = [])
    {
        $blockType = $this->blockTypes->findOrFail($blockTypeId);
        $blockType->fields()->attach($fieldId, $blockTypeFieldParams);

        $field = $blockType->fields()->findOrFail($fieldId);
        $blockType->addField($field);
    }

    public function detach($blockTypeId, $fieldId)
    {
        $blockType = $this->blockTypes->findOrFail($blockTypeId);

        $field = $blockType->fields()->findOrFail($fieldId);
        $blockType->removeField($field);

        $blockType->fields()->detach($fieldId);
    }
}
