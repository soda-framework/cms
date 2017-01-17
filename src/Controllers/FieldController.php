<?php

namespace Soda\Cms\Controllers;

use Soda\Cms\Models\Field;
use App\Http\Controllers\Controller;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class FieldController extends Controller
{
    use CrudableTrait;
    protected $hint = 'field';

    public function __construct(Field $field)
    {
        $this->model = $field;
    }
}
