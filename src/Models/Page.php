<?php
namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Soda;
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

    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = PageClosure::class;

    public function type() {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks() {
        return $this->belongsToMany(Block::class, 'page_blocks');
    }

    public function setSlugAttribute($value) {
        $this->attributes['slug'] = '/' . str_slug(trim($value, '/'));

        return $this->attributes['slug'];
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
