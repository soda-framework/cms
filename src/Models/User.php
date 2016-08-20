<?php

namespace Soda\Cms\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable {
    use OptionallyInApplicationTrait, EntrustUserTrait;
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
