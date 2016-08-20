<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Block extends Model {
    use OptionallyInApplicationTrait, DraftableTrait;

    protected $table = 'blocks';
    protected $fillable = [
        'name',
        'description',
        'identifier',
        'status',
        'application_id',
        'block_type_id',
    ];

    public function type() {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }

}
