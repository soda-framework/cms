<?php

namespace Soda\Cms\Database\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Traits\Auditable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Soda\Cms\Database\Models\Contracts\HasLocale;
use Soda\Cms\Database\Models\Traits\UserHasRoles;
use Soda\Cms\Database\Models\Contracts\UserInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements UserInterface, HasLocale, AuthenticatableContract, CanResetPasswordContract
{
    use Auditable, OptionallyBoundToApplication, UserHasRoles, Authenticatable, CanResetPassword;
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
