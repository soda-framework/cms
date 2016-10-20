<?php

namespace Soda\Cms\Database\Fields\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;

class Field extends Model implements FieldInterface
{
    use OptionallyBoundToApplication;
    protected $fillable = [
        'name',
        'description',
        'field_name',
        'field_type',
        'field_params',
        'value',
        'application_id',
    ];
    protected $table = 'fields';

    public function blockTypes()
    {
        return $this->morphedByMany('soda.block-type.model', 'fieldable');
    }

    public function pageTypes()
    {
        return $this->morphedByMany('soda.page-type.model', 'fieldable');
    }

    public static function getFieldTypes()
    {
        return app('soda.form')->getFieldTypes();
    }

    public function setFieldNameAttribute($value)
    {
        $this->attributes['field_name'] = str_slug($value, '_');
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
