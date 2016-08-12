<?php

namespace Themes\SodaTheme\Models;

use Illuminate\Auth\Authenticatable;
use Eloquent;
use Auth;
use Session;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Eloquent implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    //use Authenticatable, Authorizable, CanResetPassword;
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';
    protected $fillable = ['firstname','lastname','email', 'town', 'state', 'age',
        'gender', 'phone', 'track', 'call', 'golden', 'jmail',
        'provider', 'provider_id', 'abc_provider', 'abc_provider_id',
        'avatar', 'ip_address', 'x_ip_address', 'token', 'session',
        'last_session'];


    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }


    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }
}