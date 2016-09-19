<?php
namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Cms\Models\Traits\TreeableTrait;

class NavigationItem extends AbstractSodaClosureEntity
{
    use SoftDeletes, TreeableTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_items';
    protected $fillable = ['name', 'description', 'text', 'class', 'html', 'url'];
    protected $closure = NavigationItemClosure::class;

    public function navigation()
    {
        return $this->belongsTo(Navigation::class, 'page_type_id');
    }

}
