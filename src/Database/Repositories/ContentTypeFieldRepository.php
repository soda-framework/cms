<?php

namespace Soda\Cms\Database\Repositories;

use Soda\Cms\Database\Models\Contracts\FieldInterface;
use Soda\Cms\Database\Models\Contracts\ContentTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeFieldRepositoryInterface;

class ContentTypeFieldRepository implements ContentTypeFieldRepositoryInterface
{
    protected $contentTypes;
    protected $fields;

    public function __construct(ContentTypeInterface $contentTypes, FieldInterface $fields)
    {
        $this->contentTypes = $contentTypes;
        $this->fields = $fields;
    }

    public function attach($contentTypeId, $fieldId, $contentTypeFieldParams = [])
    {
        $contentType = $this->contentTypes->findOrFail($contentTypeId);
        $contentType->fields()->attach($fieldId, $contentTypeFieldParams);

        $field = $contentType->fields()->findOrFail($fieldId);
        $contentType->addField($field);
    }

    public function detach($contentTypeId, $fieldId)
    {
        $contentType = $this->contentTypes->findOrFail($contentTypeId);

        $field = $contentType->fields()->findOrFail($fieldId);
        $contentType->removeField($field);

        $contentType->fields()->detach($fieldId);
    }
}
