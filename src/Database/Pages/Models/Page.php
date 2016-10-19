<?php
namespace Soda\Cms\Database\Pages\Models;

use Cache;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
use Soda\Cms\Database\Pages\Interfaces\CachedPageRepositoryInterface;
use Soda\Cms\Database\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Pages\Models\Observers\PageObserver;
use Soda\Cms\Database\Support\Models\AbstractClosureEntityModel;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\Draftable;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Support\Models\Traits\Sortable;
use Soda\Cms\Database\Support\Models\Traits\Sluggable;

class Page extends AbstractClosureEntityModel implements PageInterface
{
    use SoftDeletes, Sluggable, OptionallyBoundToApplication, Sortable, Draftable, Identifiable, HasDefaultAttributes;

    protected $table = 'pages';

    public $fillable = [
        'name',
        'slug',
        'application_id',
        'status',
        'position',
        'page_type_id',
        'parent_id',
        'edit_action',
        'edit_action_type',
        'list_action',
        'list_action_type',
    ];

    protected $defaults = [
        'edit_action'      => 'soda::pages.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::pages.index',
        'list_action_type' => 'view',
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
        return $this->belongsTo(resolve_class(PageTypeInterface::class), 'page_type_id');
    }

    public function blocks()
    {
        return $this->belongsToMany(resolve_class(BlockInterface::class), 'page_blocks')->withPivot('can_create', 'can_delete');
    }

    public function getBlock($identifier)
    {
        return $this->blocks->filter(function ($item) use ($identifier) {
            return $item->identifier == $identifier;
        })->first();
    }

    public function getBlockModel($identifier)
    {
        $block = $identifier instanceof BlockInterface ? $identifier : $this->getBlock($identifier);

        if ($block) {
            return $block->model($this->id);
        }

        return app(BlockInterface::class)->newCollection();
    }

    public function blockModel($identifier)
    {
        $block = $identifier instanceof BlockInterface ? $identifier : $this->getBlock($identifier);

        if ($block) {
            return $block->modelQuery($this->id);
        }

        throw new Exception('Page does not have block: \''.$identifier.'\'.');
    }

    public function getDynamicModel()
    {
        return app(DynamicPageInterface::class);
    }

    public function pageAttributes()
    {
        return app(CachedPageRepositoryInterface::class)->getAttributesForPage($this);
    }
}
