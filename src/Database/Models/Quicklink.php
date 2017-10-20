<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Quicklink extends Model
{
    //use OptionallyBoundToApplication;

    public $fillable = [
        'text',
        'route_name',
        'route_params',
        'request_params',
        'user_id',
    ];
    protected $table = 'quicklinks';
    protected $casts = [
        'route_params'   => 'array',
        'request_params' => 'array',
    ];

    public function getUrl()
    {
        $baseUrl = route($this->route_name, $this->route_params);
        $query = http_build_query($this->getAttribute('request_params'));

        return $baseUrl.($query ? "?$query" : '');
    }
}
