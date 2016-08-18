<?php
namespace Soda\Models;

use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda;
use Soda\Models\Scopes\FromApplicationScope;
use Soda\Models\Scopes\LiveScope;
use Soda\Models\Scopes\PositionScope;
use Soda\Models\Traits\SluggableTrait;
use Soda\Models\Traits\TreeableTrait;

class Page extends Entity implements PagesInterface {
    use SoftDeletes, SluggableTrait, TreeableTrait;

    protected $table = 'pages';
    protected $dynamicModel;
    public $fillable = [
        'name',
        'description',
        'slug',
        'action_type',
        'action',
        'package',
        'application_id',
        'status_id',
        'position',
        'page_type_id',
        'edit_action',
        'edit_action_type',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */


    public static function boot() {
        parent::boot();
        static::addGlobalScope(new FromApplicationScope);
        static::addGlobalScope(new LiveScope);
        static::addGlobalScope(new PositionScope);
    }


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

    public function model() {
        if (!$this->dynamicModel) {
            $this->loadDynamicModel();
        }

        return $this->dynamicModel;
    }

    public function setModel(ModelBuilder $model) {
        $this->dynamicModel = $model;

        return $this;
    }

    protected function loadDynamicModel() {
        $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->where('page_id', $this->id)->first();

        if (!$model) {
            $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->newInstance();
        }

        return $this->setModel($model);
    }

    public static function hasFieldsOrBlocks($page) {
        if ((@$page->type->fields && @$page->type->fields->count()) || (@$page->blocks && @$page->blocks->count())) {
            return true;
        }

        return false;
    }

}
