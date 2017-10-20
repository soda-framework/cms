<?php

namespace Soda\Cms\Database\Models;

use Carbon\Carbon;
use Soda\Cms\Database\Models\Traits\Auditable;
use Soda\Cms\Database\Models\Contracts\HasLocale;
use Soda\Cms\Database\Models\Traits\UserHasRoles;
use Soda\Cms\Database\Models\Contracts\UserInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class User extends Authenticatable implements UserInterface, HasLocale
{
    use Auditable, OptionallyBoundToApplication, UserHasRoles;
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
        'locale',
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
        'last_loggedin_at',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'remember_token',
        'last_loggedin_at',
    ];

    public function updateLoginTimestamp()
    {
        $this->setAttribute('last_loggedin_at', Carbon::now());
        $this->save();
    }

    public function getLevel()
    {
        return $this->roles->max('level');
    }
}
