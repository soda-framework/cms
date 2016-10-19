<?php

namespace Soda\Cms\Http\Controllers\Old;

use Redirect;
use Soda\Cms\Http\Controllers\Traits\CrudableTrait;
use Soda\Cms\Models\Field;

class FieldController extends BaseController
{
    use CrudableTrait;
    protected $hint = 'field';

    public function __construct(Field $field)
    {
        $this->model = $field;
    }
}
