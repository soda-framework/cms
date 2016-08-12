<?php namespace Soda\Controllers;

use App\Http\Controllers\Controller;
use Soda\Models\Field;
use Redirect;

class FieldController extends Controller {

	use Traits\CrudableTrait;
	public $hint = 'field';

	public function __construct(Field $field) {
		//$this->middleware('auth');
		$this->model = $field;
	}



	public function addField(){
		//create a new field
		$field = new Field();
		//..etc..
		//$field->save();
	}
}