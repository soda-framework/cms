<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Field extends Model
{
    use OptionallyInApplicationTrait;
    protected $fillable = [
        'name',
        'description',
        'field_name',
        'field_type',
        'field_params',
        'show_in_table',
        'value',
        'application_id',
    ];
    protected $table = 'fields';

    public static function getFieldTypes()
    {
        return Soda::getFormBuilder()->getFieldTypes();
    }

    public function block_types()
    {
        return $this->morphedByMany(BlockType::class, 'fieldable');
    }

    public function page_types()
    {
        return $this->morphedByMany(PageType::class, 'fieldable');
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
