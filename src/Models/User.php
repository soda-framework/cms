<?php

namespace Soda\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{

	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'role_id',
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
		return $this->belongsTo(Role::class);
	}

}
