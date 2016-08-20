<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {
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
    public function pages() {
        return $this->hasMany(Page::class);
    }

    public function urls() {
        return $this->hasMany(ApplicationUrl::class);
    }
}
