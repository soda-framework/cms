<?php

namespace Soda\Cms\Http\Controllers;

use Soda\Cms\Models\Field;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;

class FieldController extends BaseController
{
    use CrudableTrait;
    protected $hint = 'field';

    public function __construct(Field $field)
    {
        $this->model = $field;
    }
}
