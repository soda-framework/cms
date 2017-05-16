<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Collection;
use Soda\Cms\Database\Models\Traits\Identifiable;
use Soda\Cms\Database\Observers\BlockTypeObserver;
use Soda\Cms\Database\Models\Traits\HasDynamicType;
use Soda\Cms\Database\Models\Traits\MorphToSortedMany;
use Soda\Cms\Database\Models\Traits\BuildsDynamicModels;
use Soda\Cms\Database\Models\Traits\HasDefaultAttributes;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class BlockType extends Model implements BlockTypeInterface
{
    use OptionallyBoundToApplication, Identifiable, BuildsDynamicModels, HasDefaultAttributes, MorphToSortedMany, HasDynamicType;

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
        'edit_action'      => 'soda::data.content.blocks.view',
        'edit_action_type' => 'view',
        'list_action'      => 'soda::data.content.blocks.index',
        'list_action_type' => 'view',
    ];

    public static function boot()
    {
        static::observe(BlockTypeObserver::class);

        parent::boot();
    }

    public function properties()
    {
        return $this->hasManyDynamic($this->getDynamicModel());
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
        $content = new Content;
        $contentTable = $content->getTable();
        $contentReferenceColumn = $content->getForeignKey();
        $contentIndex = 'FK_'.$this->getDynamicTableName().'_'.$contentReferenceColumn.'_content';

        $table->increments('id');
        $table->integer($contentReferenceColumn)->unsigned()->nullable();
        $table->tinyInteger('is_shared')->unsigned()->nullable();
        $table->foreign($contentReferenceColumn, $contentIndex)->references('id')->on($contentTable)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }

    public function blockQuery($contentId = null)
    {
        return $this->getDynamicModel()->fromTable($this->getAttribute('identifier'))->where(function ($q) use ($contentId) {
            $q->where('is_shared', 1);
            if ($contentId) {
                $q->orWhere('content_id', $contentId);
            }
        });
    }

    public function block($contentId = null)
    {
        $query = $this->blockQuery($contentId);

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

    public function shouldDynamicTableExist()
    {
        return true;
    }
}
