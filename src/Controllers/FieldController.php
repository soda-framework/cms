<?php namespace Soda\Cms\Controllers;

use App\Http\Controllers\Controller;
use Redirect;
use Soda\Cms\Models\Field;
use Soda\Cms\Controllers\Traits\CrudableTrait;

class FieldController extends Controller {

    use CrudableTrait;
    public $hint = 'field';

    public function __construct(Field $field) {
        //$this->middleware('auth');
        $this->model = $field;
    }


    public function addField() {
        //create a new field
        $field = new Field();
        //..etc..
        //$field->save();
    }
}
