<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Traits\BoundToApplication;
use Soda\Cms\Database\Models\Contracts\ApplicationSettingInterface;

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
        'category',
        'application_id',
    ];

    public static function getFieldTypes()
    {
        return app('soda.form')->getFieldTypes();
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getFieldValue()
    {
        return app('soda.form')->field($this)->getFieldValue();
    }

    public function parseField(Request $request)
    {
        $field = app('soda.form')->field($this);

        $field->setPrefix('settings');

        $this->setAttribute('value', $field->getSaveValue($request));

        return $this;
    }

    public function setFieldParamsAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['field_params'] = $value;
    }

    public function getFieldParamsAttribute($value)
    {
        return json_decode($value, true);
    }
}
