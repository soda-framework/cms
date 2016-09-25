<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Redirect;
use Soda\Cms\Controllers\Traits\CrudableTrait;
use Soda\Cms\Models\Field;

class FieldController extends Controller
{
    use CrudableTrait;
    protected $hint = 'field';

    public function __construct(Field $field)
    {
        $this->model = $field;
    }
}
