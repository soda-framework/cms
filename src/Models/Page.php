<?php

namespace Soda\Models;

use Soda\Models\Scopes\LiveScope;
use Soda\Models\Scopes\PositionScope;
use Soda\Models\Traits\SluggableTrait;
use Franzose\ClosureTable\Models\Entity;
use Soda\Models\Scopes\FromApplicationScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Entity implements PagesInterface
{
    use SoftDeletes;
    use SluggableTrait;

    protected $table = 'pages';
    public $fillable = ['name', 'slug', 'action_type', 'action', 'package', 'application_id', 'status_id', 'position'];

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
    protected $closure = 'Soda\Models\PageClosure';

    public function type()
    {
        return $this->belongsTo(PageType::class, 'page_type_id');
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'page_blocks');
    }
}
