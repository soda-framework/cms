<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Collection;
use Soda\Cms\Database\Models\Traits\Identifiable;
use Soda\Cms\Database\Observers\BlockTypeObserver;
use Soda\Cms\Database\Models\Traits\MorphToSortedMany;
use Soda\Cms\Database\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class BlockType extends Model implements BlockTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedMany;

    protected $table = 'block_types';

    protected $fillable = [
        'name',
        'description',
        'identifier',
        'is_shared',
        'application_id',
        'edit_action',
        'edit_action_type',
        'list_action',
        'list_action_type',
    ];

    protected $defaults = [
        'edit_action'      => 'soda::data.blocks.types.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::data.blocks.types.index',
        'list_action_type' => 'view',
    ];

    public static function boot()
    {
        static::observe(BlockTypeObserver::class);

        parent::boot();
    }

    public function fields()
    {
        return $this->morphToSortedMany(Field::class, 'fieldable')->withPivot('show_in_table');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_block_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $page = new Page;
        $pageTable = $page->getTable();
        $pageReferenceColumn = $page->getForeignKey();
        $pageIndex = 'FK_'.$this->getDynamicTableName().'_'.$pageReferenceColumn.'_pages';

        $table->increments('id');
        $table->integer($pageReferenceColumn)->unsigned()->nullable();
        $table->tinyInteger('is_shared')->unsigned()->nullable();
        $table->foreign($pageReferenceColumn, $pageIndex)->references('id')->on($pageTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }

    public function blockQuery($pageId = null)
    {
        return $this->getDynamicModel()->fromTable($this->getAttribute('identifier'))->where(function ($q) use ($pageId) {
            $q->where('is_shared', 1);
            if ($pageId) {
                $q->orWhere('page_id', $pageId);
            }
        });
    }

    public function block($pageId = null)
    {
        $query = $this->blockQuery($pageId);

        $model = $query->get();

        if (! $model) {
            $model = new Collection;
        }

        return $model;
    }

    public function getDynamicModel()
    {
        return new DynamicBlock;
    }
}
