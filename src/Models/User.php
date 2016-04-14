<?php

namespace Soda\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{


	public $title = 'user';
	public $plural_title = 'users';

	public $view_view = 'soda::standard.view';
	public $view_fields = [
		'username'=>[
			'label'=>'username',
			'type'=>'text',
			'name'=>'username'
		],
		'email'=>[
			'label'=>'email',
			'type'=>'text',
			'name'=>'email'
		],
		'password'=>[
			'label'=>'password',
			'type'=>'password',
			'name'=>'password'
		]
	];

	public $index_view = 'soda::standard.index';
	public $index_fields = [
		'username'=>[
			'label'=>'username',
			'type'=>'text',
			'name'=>'username'
		],
		'email'=>[
			'label'=>'email',
			'type'=>'text',
			'name'=>'email'
		]
	];


	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function creator()
	{
		return $this->hasMany('Soda\Content');
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function role()
	{
		return $this->hasMany('Soda\Role');
	}

}
