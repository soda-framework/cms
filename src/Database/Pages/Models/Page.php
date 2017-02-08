<?php

namespace Soda\Cms\Database\Pages\Models;

use Exception;
use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Support\Models\Traits\Draftable;
use Soda\Cms\Database\Support\Models\Traits\Sluggable;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Pages\Models\Observers\PageObserver;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Support\Models\Traits\SortableClosure;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\AdditionalClosureScopes;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class Page extends Entity implements PageInterface
{
    use SoftDeletes, Sluggable, Draftable, OptionallyBoundToApplication, Identifiable, HasDefaultAttributes, AdditionalClosureScopes, SortableClosure;

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

    public function type()
    {
        return $this->belongsTo(resolve_class('soda.page-type.model'), 'page_type_id');
    }

    public function block_types()
    {
        return $this->belongsToMany(resolve_class('soda.block-type.model'), 'page_blocks')->withPivot('min_blocks', 'max_blocks');
    }

    public function getBlockType($identifier)
    {
        $block = $this->getRelation('block_types')->filter(function ($item) use ($identifier) {
            return $item->identifier == $identifier;
        })->first();

        if (! $block) {
            $block = $this->type->blocks->filter(function ($item) use ($identifier) {
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
        return app('soda.dynamic-page.model');
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
