<?php

namespace Soda\Cms\Database\Blocks\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Database\Blocks\Observers\BlockObserver;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;

class Block extends Model implements BlockInterface
{
    use OptionallyBoundToApplication, Identifiable, HasDefaultAttributes;

    protected $table = 'blocks';

    protected $fillable = [
        'name',
        'description',
        'is_shared',
        'identifier',
        'block_type_id',
        'application_id',
        'edit_action',
        'edit_action_type',
        'list_action',
        'list_action_type',
    ];

    protected $defaults = [
        'edit_action'      => 'soda::blocks.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::blocks.index',
        'list_action_type' => 'view',
    ];

    public static function boot()
    {
        static::observe(BlockObserver::class);

        parent::boot();
    }

    public function type()
    {
        return $this->belongsTo(resolve_class(BlockTypeInterface::class), 'block_type_id');
    }

    public function modelQuery($pageId = null)
    {
        if (!$this->relationLoaded('type')) {
            $this->load('type');
        }

        return $this->getDynamicModel()->fromTable($this->type->identifier)->where('block_id', $this->getKey())->where(function ($q) use ($pageId) {
            $q->where('is_shared', 1);
            if ($pageId) {
                $q->orWhere('page_id', $pageId);
            }
        });
    }

    public function model($pageId = null)
    {
        $query = $this->modelQuery($pageId);

        $model = $query->get();

        if (!$model) {
            $model = new Collection;
        }

        return $model;
    }

    public function getDynamicModel()
    {
        return app(DynamicBlockInterface::class);
    }
}
