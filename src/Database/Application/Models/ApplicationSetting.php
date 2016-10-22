<?php

namespace Soda\Cms\Database\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Application\Interfaces\ApplicationSettingInterface;
use Soda\Cms\Database\Support\Models\Traits\BoundToApplication;

class ApplicationSetting extends Model implements ApplicationSettingInterface
{
    use BoundToApplication;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'field_name',
        'field_type',
        'field_params',
        'value',
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(resolve_class('soda.application.model'));
    }

    public static function getFieldTypes()
    {
        return app('soda.form')->getFieldTypes();
    }
}
