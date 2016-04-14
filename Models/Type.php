<?php

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Soda\Models\Scopes\FromApplicationScope;
use \Soda\Models\Scopes\LiveScope;

class Type extends Model {
	protected $table = 'page_types';
	protected $fillable = ['name', 'description'];


	public $title = 'page_type';
	public $plural_title = 'page_types';

	public $view_view = 'soda::standard.view';
	public $view_fields = [
		'name'        => [
			'label' => 'name',
			'type'  => 'text',
			'name'  => 'name',
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


	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new FromApplicationScope);
		static::addGlobalScope(new LiveScope);
	}

	public function fields() {
		return $this->hasMany(Field::class);
	}

	public function page() {
		return $this->hasMany(Page::class);
	}


	/**
	 * DUMMY SHIT.
	 * Add a new (page) type TABLE
	 */
	public function addType($fields) {
		try {
			if (!Schema::hasTable('soda_' . $this->identifier)) {
				Schema::create('soda_' . $this->identifier, function (Blueprint $table) use ($fields) {
					$table->increments('id'); //should this always be there?
					//TODO: field decoder and runner in here..
					foreach ($fields as $field) {
						$table->string($field->field_name);
					}
					$table->timestamps();
				});
			}
		} catch(\Exception $e) {
			dd($e->getMessage());
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * adds a field to an existing type.
	 */
	public function addFieldsToType($fields) {
		try {
			foreach ($fields as $field) {
				if (Schema::hasColumn('soda_' . $this->identifier, $field->field_name)) {
					//we want to alter the existing table.
					Schema::table('soda_' . $this->identifier, function ($table) use ($field) {
						$table->string($field->field_name)->change();
					});
				} else {
					//we want to make a new column
					Schema::table('soda_' . $this->identifier, function ($table) use ($field) {
						$table->string($field->field_name);
					});
				}
			}
		} catch(\Exception $e) {
			dd($e->getMessage());
			return FALSE;
		}
		return TRUE;

	}

	public static function removeFieldFromType() {
		try {
			if (Schema::hasColumn('soda_flights', '')) {
				//remove this column.
				Schema::table('soda_flights', function ($table) {
					$table->dropColumn('airline');
				});
			}
		} catch(\Exception $e) {
			dd($e->getMessage());
			return FALSE;
		}
		return TRUE;
	}

}