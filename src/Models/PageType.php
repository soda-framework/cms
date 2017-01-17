<?php

namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class PageType extends AbstractDynamicType
{
    use OptionallyInApplicationTrait, DraftableTrait;

    protected $table = 'page_types';
    public $fillable = [
        'name',
        'identifier',
        'description',
        'application_id',
        'status',
        'position',
        'package',
        'action',
        'action_type',
        'edit_action',
        'edit_action_type',
    ];

    public function fields()
    {
        return $this->morphToMany(Field::class, 'fieldable')->withPivot('position')->orderBy('pivot_position', 'asc');
    }

    public function block()
    {
        return $this->hasMany(Block::class, 'block_type_id');
    }

    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value, '_');
    }
}
