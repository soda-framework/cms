<?php

namespace Soda\Cms\Database\Pages\Repositories;

use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeFieldRepositoryInterface;

class PageTypeFieldRepository implements PageTypeFieldRepositoryInterface
{
    protected $pageTypes;
    protected $fields;

    public function __construct(PageTypeInterface $pageTypes, FieldInterface $fields)
    {
        $this->pageTypes = $pageTypes;
        $this->fields = $fields;
    }

    public function attach($pageTypeId, $fieldId, $pageTypeFieldParams = [])
    {
        $pageType = $this->pageTypes->findOrFail($pageTypeId);
        $pageType->fields()->attach($fieldId, $pageTypeFieldParams);

        $field = $pageType->fields()->findOrFail($fieldId);
        $pageType->addField($field);
    }

    public function detach($pageTypeId, $fieldId)
    {
        $pageType = $this->pageTypes->findOrFail($pageTypeId);

        $field = $pageType->fields()->findOrFail($fieldId);
        $pageType->removeField($field);

        $pageType->fields()->detach($fieldId);
    }
}
