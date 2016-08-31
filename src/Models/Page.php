<?php
namespace Soda\Cms\Models;

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
        return $this->belongsToMany(Block::class, 'page_blocks');
    }

    public function getDynamicBlock($block) {
        if(!$this->dynamicBlockLoaded($block)) {
            $this->loadDynamicBlock($block);
        }

        return $this->dynamicBlocks[$block->type->identifier];
    }

    public function dynamicBlock($block) {
        $identifier = $block->type->identifier;
        $fields = $block->type->fields;
        $id = $block->id;

        return Soda::dynamicModel('soda_' . $identifier, $fields->lists('field_name')->toArray())->where('block_id', $id)->where(function($q){
            $q->where('is_shared', 1)->orWhere('page_id', $this->id);
        });
    }

    public function loadDynamicBlock($block) {
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

}
