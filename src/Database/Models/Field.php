<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Contracts\FieldInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

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

    public static function getFieldTypes()
    {
        return app('soda.form')->getFieldTypes();
    }

    public function blockTypes()
    {
        return $this->morphedByMany(BlockType::class, 'fieldable');
    }

    public function contentTypes()
    {
        return $this->morphedByMany(ContentType::class, 'fieldable');
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
