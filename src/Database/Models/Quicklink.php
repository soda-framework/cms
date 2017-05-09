<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class Quicklink extends Model
{
    use OptionallyBoundToApplication;

    protected $table = 'quicklinks';

    public $fillable = [
        'text',
        'route_name',
        'route_params',
        'request_params',
        'user_id',
    ];

    public $casts = [
        'route_params'   => 'array',
        'request_params' => 'array',
    ];
}
