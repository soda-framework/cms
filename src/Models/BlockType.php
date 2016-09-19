<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\DynamicCreatorTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class BlockType extends AbstractDynamicType
{
    use OptionallyInApplicationTrait, DraftableTrait;

    protected $table = 'block_types';
    protected $fillable = [
        'name',
        'description',
        'status',
        'identifier',
        'application_id',
        'package',
        'action',
        'action_type',
        'edit_action',
        'edit_action_type',
    ];

    public function fields()
    {
        return $this->morphToMany(Field::class, 'fieldable')->withPivot('position')->orderBy('pivot_position', 'asc');
    }

    public function block()
    {
        return $this->hasMany(Block::class, 'block_type_id');
    }

    public function setIdentifierAttribute($value)
    {
        $this->attributes['identifier'] = str_slug($value, '_');
    }

    protected function buildDynamicTable(Blueprint $table)
    {
        $block_index = 'FK_'.$this->getDynamicTableName().'_block_id_blocks';
        $page_index = 'FK_'.$this->getDynamicTableName().'_page_id_pages';

        $table->increments('id');
        $table->integer('block_id')->unsigned()->nullable();
        $table->integer('page_id')->unsigned()->nullable();
        $table->integer('is_shared')->unsigned()->nullable();
        $table->foreign('block_id', $block_index)->references('id')->on('blocks')->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->foreign('page_id', $page_index)->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }
}
