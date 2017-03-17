<?php

namespace Soda\Cms\Database\Models;

use Exception;
use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Database\Observers\PageObserver;
use Soda\Cms\Database\Models\Traits\Draftable;
use Soda\Cms\Database\Models\Traits\Sluggable;
use Soda\Cms\Database\Models\Traits\Identifiable;
use Soda\Cms\Database\Relationships\HasOneDynamic;
use Soda\Cms\Database\Models\Traits\HasDynamicType;
use Soda\Cms\Database\Models\Traits\SortableClosure;
use Soda\Cms\Database\Models\Contracts\PageInterface;
use Soda\Cms\Database\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Models\Traits\AdditionalClosureScopes;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class Page extends Entity implements PageInterface
{
    use SoftDeletes, Sluggable, Draftable, OptionallyBoundToApplication, Identifiable, HasDefaultAttributes, AdditionalClosureScopes, SortableClosure, HasDynamicType;

    protected $table = 'pages';
    protected static $sortableGroupField = ['application_id', 'parent_id'];

    public $timestamps = true;

    public $fillable = [
        'name',
        'slug',
        'application_id',
        'status',
        'position',
        'page_type_id',
        'parent_id',
        'view_action',
        'view_action_type',
        'edit_action',
        'edit_action_type',
        'can_delete',
        'allowed_children',
    ];

    protected $defaults = [
        'view_action'      => '',
        'view_action_type' => 'view',
        'edit_action'      => 'soda::data.pages.view',
        'edit_action_type' => 'view',
    ];

    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = PageClosure::class;

    public static function boot()
    {
        static::observe(PageObserver::class);

        parent::boot();
    }

    public function properties()
    {
        return $this->hasOneDynamic($this->getDynamicModel());
    }

    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blockTypes()
    {
        return $this->belongsToMany(BlockType::class, 'page_blocks')->withPivot('min_blocks', 'max_blocks');
    }

    public function getBlockType($identifier)
    {
        $block = $this->blockTypes->filter(function ($item) use ($identifier) {
            return $item->identifier == $identifier;
        })->first();

        if (! $block && $this->type && $this->type->blockTypes) {
            $block = $this->type->blockTypes->filter(function ($item) use ($identifier) {
                return $item->identifier == $identifier;
            })->first();
        }

        return $block;
    }

    public function block($identifier)
    {
        $block = $identifier instanceof BlockTypeInterface ? $identifier : $this->getBlockType($identifier);

        if ($block) {
            return $block->blockQuery($this->getKey());
        }

        throw new Exception('Page does not have block: \''.$identifier.'\'.');
    }

    public function getBlock($identifier)
    {
        return $this->block($identifier)->get();
    }

    public function getDynamicModel()
    {
        return new DynamicPage;
    }

    public function pageAttributes()
    {
        return app('soda.page.cached-repository')->getAttributesForPage($this);
    }

    public function getPageAttribute($attribute)
    {
        $attributes = $this->pageAttributes();

        return isset($attributes[$attribute]) ? $attributes[$attribute] : null;
    }

    public function isAllowedChildren()
    {
        return $this->allowed_children ? true : false;
    }

    public function canDelete()
    {
        return $this->can_delete ? true : false;
    }
}
