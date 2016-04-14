<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 3/02/2016
 * Time: 8:51 PM
 */

namespace Soda;

use Soda\Models\ApplicationUrl;
use Soda\Models\Block;
use Soda\Models\BlockType;
use Soda\Models\ModelBuilder;
use Soda\Models\Page;
use \Route;

//TODO: MOVE ME SOMEWHERE SENSIBLE

class Soda {
	public $application = NULL;
	public $blocks = NULL;

	public function __construct() {

	}

	public function getApplication(){

		if(!$this->application){
			$this->application = ApplicationUrl::where('domain',$_SERVER['HTTP_HOST'])->first()->application()->first();
		}
		return $this->application;
	}


	public function getBlock($identifier){
		//TODO: should this be a collection instead?
		//maybe this should be somewhere else?

		if(!@$this->blocks[$identifier]){
			$this->blocks[$identifier] = Block::where('identifier', $identifier)->first();
		}

		return $this->blocks[$identifier];
	}

	public function dynamicModel($table, $fields){
		return ModelBuilder::fromTable($table, $fields);
	}

	/**
	 * renders an editable field
	 * @param $model
	 * @param $element
	 * @param $type
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function editable($model, $element, $type){
		$field_value = $model->{$element};
		if(\Request::get('soda_edit')){
			$unique = uniqid();
			if(get_class($model) == 'Soda\Models\ModelBuilder'){
				//we need to get the db name and attach to the field..
				$type = BlockType::where('identifier',$type)->first();
				$link = route('soda.dyn.inline.edit', ['type'=>$type->id, 'model'=>$model->id,'field'=>$element]);
			}
			//TODO: figure out which type of field we need to use here..
			return view('soda::inputs.inline.text', ['link'=>$link, 'element'=>$element, 'model'=>$model, 'unique'=>$unique, 'field_value' => $field_value]);
		}
		else{
			return $field_value;
		}
	}

	/**
	 * returns active if given route matches current route.
	 * TODO: move to menu class somewhere?
	 * @param $route
	 * @param string $output
	 * @return string
	 */
	public function menuActive($route, $output = 'active'){
		if (Route::currentRouteName() == $route) return $output;
	}
}