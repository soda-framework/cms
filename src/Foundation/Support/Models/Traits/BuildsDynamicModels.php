<?php

namespace Soda\Cms\Foundation\Support\Models\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use Soda\Cms\Foundation\Support\Observers\DynamicModelObserver;
use SodaForm;

trait BuildsDynamicModels
{
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
        return $this->getDynamicModelTablePrefix().str_slug($this->identifier, '-');
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
        } else {
            Throw new Exception('Table '.$table.' does not exist');
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

    public function addField(Field $field)
    {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (!Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                SodaForm::field($field)->addToModel($table)->after('id');
            });
        }

        return $this;
    }

    public function removeField(Field $field)
    {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                SodaForm::field($field)->removeFromModel($table);
            });
        }

        return $this;
    }

    public function syncFields()
    {
        // todo
    }
}
