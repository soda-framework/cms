<?php
namespace Soda\Models;


use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Models\Traits\TreeableTrait;

class NavigationItem extends Entity implements NavigationItemInterface
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_items';
    protected $fillable = ['name', 'description', 'text', 'class', 'html', 'url'];
    use TreeableTrait;
    protected $closure = NavigationItemClosure::class;

    public function navigation()
    {
        return $this->belongsTo(Navigation::class, 'page_type_id');
    }

}