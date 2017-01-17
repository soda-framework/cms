<?php

namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\TreeableTrait;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Models\Traits\PositionableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Page extends AbstractSodaClosureEntity
{
    use SoftDeletes, SluggableTrait, TreeableTrait, OptionallyInApplicationTrait, PositionableTrait, DraftableTrait;

    protected $table = 'pages';
    protected $dynamicModel;
    public $fillable = [
        'name',
        'description',
        'slug',
        'application_id',
        'status',
        'position',
        'page_type_id',
        'parent_id',
        'package',
        'action',
        'action_type',
        'edit_action',
        'edit_action_type',
    ];

    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = PageClosure::class;

    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'page_blocks');
    }

    public function model()
    {
        if (! $this->dynamicModel) {
            $this->loadDynamicModel();
        }

        return $this->dynamicModel;
    }

    public function setModel(ModelBuilder $model)
    {
        $this->dynamicModel = $model;

        return $this;
    }

    protected function loadDynamicModel()
    {
        $model = ModelBuilder::fromTable('soda_'.$this->type->identifier)->where('page_id', $this->id)->first();

        if (! $model) {
            $model = ModelBuilder::fromTable('soda_'.$this->type->identifier)->newInstance();
        }

        return $this->setModel($model);
    }

    public static function hasFieldsOrBlocks($page)
    {
        if ((@$page->type->fields && @$page->type->fields->count()) || (@$page->blocks && @$page->blocks->count())) {
            return true;
        }

        return false;
    }
}
