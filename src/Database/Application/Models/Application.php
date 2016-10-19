<?php

namespace Soda\Cms\Database\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Application\Interfaces\ApplicationInterface;

class Application extends Model implements ApplicationInterface
{
    protected $table = 'applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(resolve_class('soda.page.model'));
    }

    public function urls()
    {
        return $this->hasMany(resolve_class('soda.application-urls.model'));
    }
}
