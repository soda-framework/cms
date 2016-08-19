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

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages() {
        return $this->hasMany('Soda\Cms\Models\Page');
    }

    public function urls() {
        return $this->hasMany(ApplicationUrl::class);
    }

}
