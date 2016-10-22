<?php

namespace Soda\Cms\Database\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Rutorika\Sortable\MorphToSortedManyTrait;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Support\AbstractDynamicType;
use Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class PageType extends Model implements PageTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedManyTrait;

    protected $table = 'page_types';

    public $fillable = [
        'name',
        'identifier',
        'description',
        'application_id',
        'view_action',
        'view_action_type',
        'edit_action',
        'edit_action_type',
    ];

    protected $defaults = [
        'view_action'      => '',
        'view_action_type' => 'view',
        'edit_action'      => 'soda::data.pages.view',
        'edit_action_type' => 'view',
    ];

    public function fields()
    {
        return $this->morphToSortedMany(resolve_class('soda.field.model'), 'fieldable')->withPivot('show_in_table');
    }

    public function pages()
    {
        return $this->hasMany(resolve_class('soda.page.model'), 'page_type_id');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_page_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $reference_column = $this->pages()->getForeignKey();
        $reference_table = $this->pages()->getRelated()->getTable();
        $reference_index = 'FK_'.$this->getDynamicTableName().'_'.$reference_column.'_'.$reference_table;

        $table->increments('id');
        $table->integer($reference_column)->unsigned()->nullable();
        $table->foreign($reference_column, $reference_index)->references('id')->on($reference_table)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }
}
