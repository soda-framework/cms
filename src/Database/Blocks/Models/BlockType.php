<?php

namespace Soda\Cms\Database\Blocks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Forms\Fields\FormFieldInterface;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;
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
        'edit_action'      => 'soda::blocks.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::blocks.index',
        'list_action_type' => 'view',
    ];

    public function fields()
    {
        return $this->morphToMany(resolve_class(FormFieldInterface::class), 'fieldable')->withPivot('position')->orderBy('pivot_position', 'asc');
    }

    public function blocks()
    {
        return $this->hasMany(resolve_class(BlockInterface::class), 'block_type_id');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_block_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $blockTable = $this->blocks()->getRelated()->getTable();
        $blockReferenceColumn = $this->blocks()->getForeignKey();
        $blockIndex = 'FK_'.$this->getDynamicTableName().'_'.$blockReferenceColumn.'_'.$blockTable;

        $page = app(PageInterface::class);
        $pageTable = $page->getTable();
        $pageReferenceColumn = 'page_id';
        $pageIndex = 'FK_'.$this->getDynamicTableName().'_page_id_pages';

        $table->increments('id');
        $table->integer($blockReferenceColumn)->unsigned()->nullable();
        $table->integer('page_id')->unsigned()->nullable();
        $table->integer('is_shared')->unsigned()->nullable();
        $table->foreign($blockReferenceColumn, $blockIndex)->references('id')->on($blockTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->foreign($pageReferenceColumn, $pageIndex)->references('id')->on($pageTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }
}
