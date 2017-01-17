<?php

namespace Soda\Cms\Database\Blocks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Rutorika\Sortable\MorphToSortedManyTrait;
use Soda\Cms\Database\Blocks\Observers\BlockTypeObserver;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class BlockType extends Model implements BlockTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedManyTrait;

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
        'edit_action'      => 'soda::data.pages.blocks.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::data.pages.blocks.index',
        'list_action_type' => 'view',
    ];

    public static function boot()
    {
        static::observe(BlockTypeObserver::class);

        parent::boot();
    }

    public function fields()
    {
        return $this->morphToSortedMany(resolve_class('soda.field.model'), 'fieldable')->withPivot('show_in_table');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_block_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $page = app('soda.page.model');
        $pageTable = $page->getTable();
        $pageReferenceColumn = $page->getForeignKey();
        $pageIndex = 'FK_'.$this->getDynamicTableName().'_'.$pageReferenceColumn.'_pages';

        $blockTable = $page->block_types()->getRelated()->getTable();
        $blockReferenceColumn = $page->block_types()->getRelated()->getForeignKey();
        $blockIndex = 'FK_'.$this->getDynamicTableName().'_'.$blockReferenceColumn.'_'.$blockTable;

        $table->increments('id');
        $table->integer($blockReferenceColumn)->unsigned()->nullable();
        $table->integer($pageReferenceColumn)->unsigned()->nullable();
        $table->tinyInteger('is_shared')->unsigned()->nullable();
        $table->foreign($blockReferenceColumn, $blockIndex)->references('id')->on($blockTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->foreign($pageReferenceColumn, $pageIndex)->references('id')->on($pageTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }

    public function blockQuery($pageId = null)
    {
        return $this->getDynamicModel()->fromTable($this->getAttribute('identifier'))->where('block_type_id', $this->getKey())->where(function ($q) use ($pageId) {
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
        return app('soda.dynamic-block.model');
    }
}
