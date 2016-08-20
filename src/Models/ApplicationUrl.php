<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationUrl extends Model {
    protected $table = 'application_urls';

    protected $fillable = [
        'domain',
        'application_id',
    ];

    public function application() {
        return $this->belongsTo(Application::class);
    }
}
