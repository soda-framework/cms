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

    public function type() {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }

    public function setIdentifierAttribute($value) {
        $this->attributes['identifier'] = str_slug($value);
    }

    protected function loadDynamicModel() {
        if (!$this->type) {
            $this->load('type');
        }

        $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->where($this->getRelatedField(), $this->id)->get();

        if (!$model) {
            $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->newInstance();
        }

        return $this->setModel($model);
    }
}
