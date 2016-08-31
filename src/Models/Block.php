<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\HasDynamicModelTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Block extends Model {
    use OptionallyInApplicationTrait, DraftableTrait, HasDynamicModelTrait;

    protected $table = 'blocks';
    protected $fillable = [
        'name',
        'description',
        'identifier',
        'status',
        'application_id',
        'block_type_id',
        'is_shared',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();

        static::saved(function ($block) {
            if ($block->isDirty('is_shared')) {
                if (!$block->type) {
                    $block->load('type');
                }
                ModelBuilder::fromTable('soda_' . $block->type->identifier)->where($block->getRelatedField(), $block->id)->update(['is_shared' => $block->is_shared]);
            }
        });
    }

    public function type() {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }

    public function setIdentifierAttribute($value) {
        $this->attributes['identifier'] = str_slug($value);
    }
}
