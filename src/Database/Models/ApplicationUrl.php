<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Contracts\ApplicationUrlInterface;

class ApplicationUrl extends Model implements ApplicationUrlInterface
{
    protected $table = 'application_urls';

    protected $fillable = [
        'domain',
        'application_id',
        'primary',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function scopePrimary($q)
    {
        return $q->where('primary', 1);
    }
}
