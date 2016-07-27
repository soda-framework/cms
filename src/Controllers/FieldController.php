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

	public function index(){

		$filter = \DataFilter::source($this->model);
		$filter->add('name','name', 'text');;
		$filter->submit('Search');
		$filter->reset('Clear');
		$filter->build();

		$grid = \DataGrid::source($filter);  //same source types of DataSet
		$grid->add('name','Field Name', true); //field name, label, sortable
		$grid->add('field_type','Field Type', true); //field name, label, sortable
		$grid->add('description','Field Description', true); //field name, label, sortable
		$grid->add('{{ $id }}', 'Options')->cell(function ($value){
			$edit = "<a href='".route('soda.'.$this->hint.'.edit', [$value])."' class='btn btn-warning'><span class='fa fa-pencil'></span> Edit</a> ";
			$edit .= "<a href='".route('soda.'.$this->hint.'.delete', [$value])."' class='btn btn-danger'><span class='fa fa-pencil'></span> Delete</a>";
			return $edit;
		});
		$grid->orderBy('id','desc'); //default orderby
		$grid->paginate(10)->getGrid('soda::partials.grid');
		$hint = $this->hint;
		return view('soda::'.$this->hint.'.index', compact('filter', 'grid', 'hint'));
	}

	public function view($id = NULL){
		if(!$id){
			$field = $this->model;
		}
		else{
			$field = $this->model->find($id);
		}
		$hint = $this->hint;
		return view('soda::'.$this->hint.'.view', compact('field', 'hint'));
	}


	public function addField(){
		//create a new field
		$field = new Field();
		//..etc..
		//$field->save();
	}
}