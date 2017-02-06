<?php

namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class PageType extends AbstractDynamicType
{
    use DraftableTrait, OptionallyInApplicationTrait;

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
        'allowed_children',
        'can_create',
    ];
    protected $table = 'page_types';

    public function fields()
    {
        return $this->morphToMany(Field::class, 'fieldable')->withPivot('position')->orderBy('pivot_position', 'asc');
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'page_type_blocks')->withPivot('can_create', 'can_delete');
    }

    public function subpageTypes()
    {
        return $this->belongsToMany(self::class, 'page_type_subpage_types', 'page_type_id', 'subpage_type_id');
    }

    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value, '_');
    }
}
