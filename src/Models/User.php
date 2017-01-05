<?php

namespace Soda\Cms\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Models\Traits\PermissionsUserTrait;

class User extends Authenticatable
{
    use OptionallyInApplicationTrait, PermissionsUserTrait;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
