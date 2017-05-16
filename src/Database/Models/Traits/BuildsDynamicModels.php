<?php

namespace Soda\Cms\Database\Models\Traits;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Database\Observers\DynamicModelObserver;
use Soda\Cms\Database\Models\Contracts\FieldInterface;

trait BuildsDynamicModels
{
    abstract public function getTable();

    abstract public function getDynamicModelTablePrefix();

    abstract public function buildDynamicTable(Blueprint $table);

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

        if (Schema::hasTable($table)) {
            throw new Exception('Table '.$table.' already exists');
        }

        Schema::create($table, function (Blueprint $table) {
            $this->buildDynamicTable($table);
        });

        if (! $this->relationLoaded('fields')) {
            $this->load('fields');
        }

        if ($fields = $this->getRelation('fields')) {
            $this->addFields($fields);
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

        if (! Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {
                $this->buildDynamicTable($table);
            });
        }

        if (! Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                app('soda.form')->field($field)->addToModel($table)->nullable()->after('id');
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
                app('soda.form')->field($field)->removeFromModel($table);
            });
        }

        return $this;
    }

    public function syncFields()
    {
        // todo
    }

    public function shouldDynamicTableExist()
    {
        if (! $this->relationLoaded('fields')) {
            $this->load('fields');
        }

        $fields = $this->getRelation('fields');

        if ($fields && count($fields)) {
            return true;
        }

        return false;
    }

    public function dynamicTableExists()
    {
        $contentTypeTable = $this->getDynamicModelTablePrefix().$this->getAttribute('identifier');
        if (Schema::hasTable($contentTypeTable)) {
            return true;
        }

        return false;
    }
}
