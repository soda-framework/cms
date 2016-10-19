<?php

namespace Soda\Cms\Foundation\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Foundation\Support\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Foundation\User\Interfaces\UserInterface;
use Soda\Cms\Foundation\User\Traits\UserRolesTrait;

class User extends Authenticatable implements UserInterface
{
    use OptionallyInApplicationTrait, UserRolesTrait;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
