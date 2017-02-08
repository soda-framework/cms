<?php

namespace Soda\Cms\Database\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Database\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Database\Support\Models\Traits\Identifiable;
use Soda\Cms\Database\Support\Models\Traits\MorphToSortedMany;
use Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Support\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class PageType extends Model implements PageTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedMany;

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
        'allowed_children',
        'can_create',
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

    public function block_types()
    {
        return $this->belongsToMany(resolve_class('soda.block-type.model'), 'page_type_block_types')->withPivot('min_blocks', 'max_blocks');
    }

    public function subpage_types()
    {
        return $this->belongsToMany(static::class, 'page_type_subpage_types', 'page_type_id', 'subpage_type_id');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_page_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $related = $this->pages()->getRelated();
        $reference_column = $related->getForeignKey();
        $reference_table = $related->getTable();
        $reference_index = 'FK_'.$this->getDynamicTableName().'_'.$reference_column.'_'.$reference_table;

        $table->increments('id');
        $table->integer($reference_column)->unsigned()->nullable();
        $table->foreign($reference_column, $reference_index)->references('id')->on($reference_table)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }
}
