<?php

namespace Soda\Cms\Foundation\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationInterface;
use Soda\Cms\Foundation\Application\Interfaces\ApplicationUrlInterface;

class ApplicationUrl extends Model implements ApplicationUrlInterface
{
    protected $table = 'application_urls';

    protected $fillable = [
        'domain',
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(resolve_class(ApplicationInterface::class));
    }
}
