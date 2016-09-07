<?php
namespace Soda\Cms\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda;
use Soda\Cms\Components\Status;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\HasDynamicModelTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Models\Traits\PositionableTrait;
use Soda\Cms\Models\Traits\SluggableTrait;
use Soda\Cms\Models\Traits\TreeableTrait;

class Page extends AbstractSodaClosureEntity {
    use SoftDeletes, SluggableTrait, TreeableTrait, OptionallyInApplicationTrait, PositionableTrait, DraftableTrait, HasDynamicModelTrait;

    protected $table = 'pages';
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

    protected $pageAttributes;

    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = PageClosure::class;

    public static function createRoot() {
        return static::create([
            'name'           => 'root',
            'parent_id'      => null,
            'application_id' => Soda::getApplication()->id,
            'position'       => 0,
            'real_depth'     => 0,
            'status'         => Status::LIVE,
        ]);
    }

    public function type() {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks() {
        return $this->belongsToMany(Block::class, 'page_blocks')->withPivot('can_create', 'can_delete');
    }

    public function getBlock($identifier) {
        return $this->blocks->filter(function($item) use ($identifier) {
           return $item->identifier == $identifier;
        })->first();
    }

    public function getBlockModel($identifier) {
        $block = $identifier instanceof Block ?  $identifier :  $this->getBlock($identifier);

        if($block) {
            return $block->model($this->id);
        }

        return new Collection;
    }

    public function blockModel($identifier) {
        $block = $identifier instanceof Block ?  $identifier :  $this->getBlock($identifier);

        if($block) {
            return $block->modelQuery($this->id);
        }

        throw new Exception('Page does not have block: \'' . $identifier . '\'.');
    }

    public function setSlugAttribute($value) {
        $this->attributes['slug'] = $this->fixSlug($value);
    }

    public function setIdentifierAttribute($value) {
        $this->attributes['identifier'] = str_slug($value);
    }

    public static function hasFieldsOrBlocks($page) {
        if ((@$page->type->fields && @$page->type->fields->count()) || (@$page->blocks && @$page->blocks->count())) {
            return true;
        }

        return false;
    }

    public function pageAttributes() {
        if (!$this->pageAttributes) {
            $this->loadPageAttributes();
        }

        return $this->pageAttributes;
    }

    public function setPageAttributes($model) {
        $this->pageAttributes = $model;

        return $this;
    }

    protected function loadPageAttributes() {
        if (!$this->type) {
            $this->load('type');
        }

        if(!$this->type) {
            $model = new ModelBuilder;
        } else {
            $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->where($this->getRelatedField(), $this->id)->first();

            if (!$model) {
                $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->newInstance();
            }
        }

        return $this->setPageAttributes($model);
    }

    public function getRelatedField() {
        return 'page_id';
    }
}
