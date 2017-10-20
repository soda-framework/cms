<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Database\Models\Traits\Identifiable;
use Soda\Cms\Database\Models\Traits\MorphToSortedMany;
use Soda\Cms\Database\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Models\Contracts\ContentTypeInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class ContentType extends Model implements ContentTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedMany;

    public $fillable = [
        'name',
        'identifier',
        'description',
        'application_id',
        'view_action',
        'view_action_type',
        'edit_action',
        'edit_action_type',
        'is_folder',
        'is_publishable',
        'is_sluggable',
        'is_movable',
        'is_creatable',
    ];
    protected $table = 'content_types';
    protected $defaults = [
        'view_action'      => '',
        'view_action_type' => 'view',
        'edit_action'      => 'soda::data.content.view',
        'edit_action_type' => 'view',
    ];

    public function fields()
    {
        return $this->morphToSortedMany(Field::class, 'fieldable')->withPivot('show_in_table');
    }

    public function blockTypes()
    {
        return $this->belongsToMany(BlockType::class, 'content_type_block_types')->withPivot('min_blocks', 'max_blocks', 'is_orderable');
    }

    public function pageTypes()
    {
        return $this->belongsToMany(static::class, 'content_folder_page_types', 'folder_type_id', 'page_type_id');
    }

    public function getDynamicModelTablePrefix()
    {
        return '_content_';
    }

    public function buildDynamicTable(Blueprint $table)
    {
        $related = $this->content()->getRelated();
        $reference_column = $related->getForeignKey();
        $reference_table = $related->getTable();
        $reference_index = 'FK_'.$this->getDynamicTableName().'_'.$reference_column.'_'.$reference_table;

        $table->increments('id');
        $table->integer($reference_column)->unsigned()->nullable();
        $table->foreign($reference_column, $reference_index)->references('id')->on($reference_table)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'content_type_id');
    }
}
