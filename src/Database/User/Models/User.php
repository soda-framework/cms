<?php

namespace Soda\Cms\Database\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\UserHasRoles;
use Soda\Cms\Database\User\Interfaces\UserInterface;

class User extends Authenticatable implements UserInterface
{
    use OptionallyBoundToApplication, UserHasRoles;
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
