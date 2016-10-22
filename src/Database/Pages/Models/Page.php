<?php
namespace Soda\Cms\Database\Pages\Models;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Models\Observers\PageObserver;
use Soda\Cms\Database\Support\Models\AbstractClosureEntityModel;
use Soda\Cms\Database\Support\Models\Traits\Draftable;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\Sluggable;
use Soda\Cms\Database\Support\Models\Traits\Sortable;

class Page extends AbstractClosureEntityModel implements PageInterface
{
    use SoftDeletes, Sluggable, OptionallyBoundToApplication, Sortable, Draftable, Identifiable, HasDefaultAttributes;

    protected $table = 'pages';

    protected static $sortableGroupField = ['application_id', 'parent_id'];

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

    public function blocks()
    {
        return $this->belongsToMany(resolve_class('soda.block.model'), 'page_blocks')->withPivot('min_blocks', 'max_blocks');
    }

    public function getBlock($identifier)
    {
        return $this->getRelation('blocks')->filter(function ($item) use ($identifier) {
            return $item->identifier == $identifier;
        })->first();
    }

    public function blockModel($identifier)
    {
        $block = $identifier instanceof BlockInterface ? $identifier : $this->getBlock($identifier);

        if ($block) {
            return $block->modelQuery($this->getKey());
        }

        throw new Exception('Page does not have block: \''.$identifier.'\'.');
    }

    public function getBlockModel($identifier)
    {
        return $this->blockModel($identifier)->get();
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
}
