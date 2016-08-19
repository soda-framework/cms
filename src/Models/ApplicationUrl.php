<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationUrl extends Model {

    protected $table = 'application_urls';


    public function application() {
        return $this->belongsTo(Application::class);
    }
}
