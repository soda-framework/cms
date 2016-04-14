<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 6/02/2016
 * Time: 5:37 PM
 */

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
	protected $fillable = ['name', 'field_name'];
	protected $table = 'fields';

	public $title = 'field';
	public $plural_title = 'fields';

	public $view_view = 'soda::standard.view';
	public $view_fields = [
		'name'        => [
			'label' => 'name',
			'type'  => 'text',
			'name'  => 'name',
		],
		'field_type'  => [
			'label' => 'field type',
			'type'  => 'text',
			'name'  => 'field_type',
		],
		'description' => [
			'label' => 'description',
			'type'  => 'textarea',
			'name'  => 'description',
		],
	];

	public $index_view = 'soda::standard.index';
	public $index_fields = [
		'name' => [
			'label' => 'name',
			'type'  => 'text',
			'name'  => 'name',
		],
	];

	public function block_types(){
		return $this->morphedByMany(BlockType::class, 'fieldable');
	}

	public function page_types(){
		return $this->morphedByMany(PageType::class, 'fieldable');
	}
}