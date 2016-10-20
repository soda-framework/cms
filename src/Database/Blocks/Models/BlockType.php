<?php

namespace Soda\Cms\Database\Blocks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class BlockType extends Model implements BlockTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes;

    protected $table = 'block_types';

    protected $fillable = [
        'name',
        'description',
        'identifier',
        'application_id',
        'edit_action',
        'edit_action_type',
        'list_action',
        'list_action_type',
    ];

    protected $defaults = [
        'edit_action'      => 'soda::data.page-blocks.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::data.page-blocks.index',
        'list_action_type' => 'view',
    ];

    public function fields()
    {
        return $this->morphToMany(resolve_class('soda.field.model'), 'fieldable')->withPivot('position', 'show_in_table')->orderBy('pivot_position', 'asc');
    }

    public function blocks()
    {
        return $this->hasMany(resolve_class('soda.block.model'), 'block_type_id');
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

        $blockTable = $page->blocks()->getRelated()->getTable();
        $blockReferenceColumn = $page->blocks()->getRelated()->getForeignKey();
        $blockIndex = 'FK_'.$this->getDynamicTableName().'_'.$blockReferenceColumn.'_'.$blockTable;

        $table->increments('id');
        $table->integer($blockReferenceColumn)->unsigned()->nullable();
        $table->integer($pageReferenceColumn)->unsigned()->nullable();
        $table->integer('is_shared', 1)->unsigned()->nullable();
        $table->foreign($blockReferenceColumn, $blockIndex)->references('id')->on($blockTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->foreign($pageReferenceColumn, $pageIndex)->references('id')->on($pageTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }
}
