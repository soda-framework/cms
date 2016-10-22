<?php

namespace Soda\Cms\Database\Users\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\UserHasRoles;
use Soda\Cms\Database\Users\Interfaces\UserInterface;

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
        'last_loggedin_at',
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
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_loggedin_at'
    ];

    public function updateLoginTimestamp()
    {
        $this->setAttribute('last_loggedin_at', Carbon::now());
        $this->save();
    }
}
