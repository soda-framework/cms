<?php
namespace Soda\Models;

use Franzose\ClosureTable\Models\Entity;
use Soda\Models\Scopes\FromApplicationScope;
use Soda\Models\Scopes\LiveScope;
use Soda\Models\Scopes\PositionScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Models\Traits\SluggableTrait;
use Soda\Models\Traits\TreeableTrait;

class Page extends Entity implements PagesInterface
{
    use SoftDeletes;
    use SluggableTrait;
    use TreeableTrait;

    protected $table = 'pages';
    public $fillable = ['name', 'description', 'slug', 'action_type', 'action', 'package', 'application_id', 'status_id', 'position', 'page_type_id', 'edit_action', 'edit_action_type'];

    /**
     * The table associated with the model.
     *
     * @var string
     */



    public static function boot()
    {
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

    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'page_blocks');
    }

    public static function hasFieldsOrBlocks($page){
        if((@$page->type->fields && @$page->type->fields->count()) || (@$page->blocks && @$page->blocks->count())){
            return true;
        }
        return false;
    }

}
