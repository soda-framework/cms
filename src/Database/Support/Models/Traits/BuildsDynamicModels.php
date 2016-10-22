<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\Support\Observers\DynamicModelObserver;
use Soda\Cms\Support\Facades\Form;

trait BuildsDynamicModels
{
    abstract function getTable();

    abstract function getDynamicModelTablePrefix();

    abstract function buildDynamicTable(Blueprint $table);

    public static function bootBuildsDynamicModels()
    {
        static::observe(DynamicModelObserver::class);
    }

    public function getDynamicType()
    {
        $table = $this->getTable();

        return preg_replace('/_types$/', '', $table);
    }

    public function getDynamicTableName()
    {
        return $this->getDynamicModelTablePrefix().str_slug($this->getAttribute('identifier'), '-');
    }

    public function createTable()
    {
        $table = $this->getDynamicTableName();

        if (!Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {
                $this->buildDynamicTable($table);
            });
        } else {
            Throw new Exception('Table '.$table.' already exists');
        }

        return $this;
    }

    public function deleteTable()
    {
        $table = $this->getDynamicTableName();

        if (Schema::hasTable($table)) {
            $reference_column = $this->getDynamicType().'_id';
            $reference_table = $this->getDynamicType().'s';
            $reference_index = 'FK_'.$this->getDynamicTableName().'_'.$reference_column.'_'.$reference_table;

            Schema::table($table, function (Blueprint $table) use ($reference_index) {
                $table->dropForeign($reference_index);
            });

            Schema::rename($table, $table.'_deleted_'.Carbon::now()->timestamp);
        }

        return $this;
    }

    public function addFields($fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function addField(FieldInterface $field)
    {
        $table = $this->getDynamicTableName();
        $field_name = $field->getAttribute('field_name');

        if (!Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                Form::field($field)->addToModel($table)->after('id');
            });
        }

        return $this;
    }

    public function removeField(FieldInterface $field)
    {
        $table = $this->getDynamicTableName();
        $field_name = $field->getAttribute('field_name');

        if (Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                Form::field($field)->removeFromModel($table);
            });
        }

        return $this;
    }

    public function syncFields()
    {
        // todo
    }
}
