<?php

namespace Soda\Cms\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\DynamicCreatorTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class PageType extends Model {
    use OptionallyInApplicationTrait, DraftableTrait, DynamicCreatorTrait;

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

    public function fields() {
        return $this->morphToMany(Field::class, 'fieldable');
    }

    public function block() {
        return $this->hasMany(Block::class, 'block_type_id');
    }
}
