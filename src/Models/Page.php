<?php
namespace Soda\Cms\Models;

use Exception;
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

    protected $dynamicBlocks = [];

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

    public function getDynamicBlock(Block $block) {
        if(!$this->dynamicBlockLoaded($block)) {
            $this->loadDynamicBlock($block);
        }

        return $this->dynamicBlocks[$block->type->identifier];
    }

    public function dynamicBlockByIdentifier($identifier) {
        $block = $this->blocks()->whereIdentifier($identifier)->first();

        if(!$block) {
            Throw new Exception('Block with identifier ' . $identifier . ' not found.');
        }

        return $this->dynamicBlock($block);
    }

    public function dynamicBlock(Block $block) {
        $identifier = $block->type->identifier;
        $fields = $block->type->fields;
        $id = $block->id;

        return Soda::dynamicModel('soda_' . $identifier, $fields->lists('field_name')->toArray())->where('block_id', $id)->where(function($q){
            $q->where('is_shared', 1)->orWhere('page_id', $this->id);
        });
    }

    public function loadDynamicBlock(Block $block) {
        $identifier = $block->type->identifier;
        $this->dynamicBlocks[$identifier] = $this->dynamicBlock($block)->get();
    }

    public function dynamicBlockLoaded($identifier) {
        if($identifier instanceOf Block) {
            $identifier = $identifier->type->identifier;
        }

        return isset($this->dynamicBlocks[$identifier]);
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

    protected function loadDynamicModel() {
        if (!$this->type) {
            $this->load('type');
        }

        $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->where($this->getRelatedField(), $this->id)->first();

        if (!$model) {
            $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->newInstance();
        }

        return $this->setModel($model);
    }

}
