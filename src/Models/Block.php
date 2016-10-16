<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\HasDynamicModelTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Block extends Model
{
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

    protected $dynamicModel;

    public function type()
    {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }

    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value);
    }

    public function modelQuery($page_id = null)
    {
        if (!$this->relationLoaded('type')) {
            $this->load('type');
        }

        return ModelBuilder::fromTable('soda_'.$this->type->identifier)->where($this->getRelatedField(), $this->id)->where(function ($q) use ($page_id) {
            $q->where('is_shared', 1);
            if ($page_id) {
                $q->orWhere('page_id', $page_id);
            }
        });
    }

    public function model($page_id = null)
    {
        $query = $this->modelQuery($page_id);

        $model = $query->get();

        if (!$model) {
            $model = new Collection;
        }

        return $model;
    }

    public function getRelatedField()
    {
        return 'block_id';
    }
}
