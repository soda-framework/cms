<?php

namespace Soda\Cms\Database\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Application\Interfaces\ApplicationUrlInterface;

class ApplicationUrl extends Model implements ApplicationUrlInterface
{
    protected $table = 'application_urls';

    protected $fillable = [
        'domain',
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(resolve_class('soda.application.model'));
    }
}
