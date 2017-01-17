<?php

namespace Soda\Controllers;

use Soda\Models\Field;
use App\Http\Controllers\Controller;

class FieldController extends Controller
{
    use    Traits\CrudableTrait;
    use Traits\CrudableTrait;
    public $type = 'fields';
    public $index_view = 'soda::crudable.index';
    public $view_view = 'soda::crudable.view';

    public function __construct(Field $field)
    {
        //$this->middleware('auth');
        $this->model = $field;
    }

    public function addField()
    {
        //create a new field
        $field = new Field();
        //..etc..
        //$field->save();
    }
}
