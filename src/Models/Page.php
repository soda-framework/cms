<?php

namespace Soda\Cms\Models;

use Soda;
use Exception;
use Soda\Cms\Support\Constants;
use Soda\Cms\Models\Traits\TreeableTrait;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\SluggableTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Models\Traits\PositionableTrait;
use Soda\Cms\Models\Traits\HasDynamicModelTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Page extends AbstractSodaClosureEntity
{
    use SoftDeletes, DraftableTrait, SluggableTrait, TreeableTrait, OptionallyInApplicationTrait, PositionableTrait, HasDynamicModelTrait;

    public $fillable = [
        'name',
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
        'can_delete',
        'allowed_children',
    ];
    protected $table = 'pages';
    protected $pageAttributes;
    public $timestamps = true;

    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = PageClosure::class;

    public static function createRoot()
    {
        return static::create([
            'name'           => 'root',
            'parent_id'      => null,
            'application_id' => Soda::getApplication()->id,
            'position'       => 0,
            'real_depth'     => 0,
            'status'         => Constants::STATUS_LIVE,
        ]);
    }

    public static function hasFieldsOrBlocks($page)
    {
        if ((@$page->type->fields && @$page->type->fields->count()) || (@$page->blocks && @$page->blocks->count()) || (@$page->type->blocks && @$page->type->blocks->count())) {
            return true;
        }

        return false;
    }

    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'page_blocks')->withPivot('can_create', 'can_delete');
    }

    public function getBlock($identifier)
    {
        $block = $this->blocks->filter(function ($item) use ($identifier) {
            return $item->identifier == $identifier;
        })->first();

        if (! $block) {
            $block = $this->type->blocks->filter(function ($item) use ($identifier) {
                return $item->identifier == $identifier;
            })->first();
        }

        return $block;
    }

    public function getBlockModel($identifier)
    {
        $block = $identifier instanceof Block ? $identifier : $this->getBlock($identifier);

        if ($block) {
            return $block->model($this->id);
        }

        return new Collection;
    }

    public function blockModel($identifier)
    {
        $block = $identifier instanceof Block ? $identifier : $this->getBlock($identifier);

        if ($block) {
            return $block->modelQuery($this->id);
        }

        throw new Exception('Page does not have block: \''.$identifier.'\'.');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $this->fixSlug($value);
    }

    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value);
    }

    public function pageAttributes()
    {
        if (! $this->pageAttributes) {
            if ($ttl = config('soda.cache.page-data')) {
                $attributes = \Cache::remember(static::getDataCacheKey($this->id), is_int($ttl) ? $ttl : config('soda.cache.default-ttl'), function () {
                    return $this->loadPageAttributes();
                });
            } else {
                $attributes = $this->loadPageAttributes();
            }

            $this->setPageAttributes($attributes);
        }

        return $this->pageAttributes;
    }

    public function setPageAttributes($model)
    {
        $this->pageAttributes = $model;

        return $this;
    }

    public function getRelatedField()
    {
        return 'page_id';
    }

    protected function loadPageAttributes()
    {
        if (! $this->relationLoaded('type')) {
            $this->load('type');
        }

        if (! $this->relationLoaded('type')) {
            $model = new ModelBuilder;
        } else {
            $model = ModelBuilder::fromTable('soda_'.$this->type->identifier)->where($this->getRelatedField(), $this->id)->first();

            if (! $model) {
                $model = ModelBuilder::fromTable('soda_'.$this->type->identifier)->newInstance();
            }
        }

        return $model;
    }

    public static function getDataCacheKey($pageId)
    {
        return 'soda.'.\Soda::getApplication()->id.'.page.'.$pageId.'.data';
    }
}
