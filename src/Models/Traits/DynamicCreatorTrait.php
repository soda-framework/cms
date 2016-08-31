<?php

namespace Soda\Cms\Models\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SodaForm;

trait DynamicCreatorTrait {
    public function getDynamicType() {
        $table = $this->getTable();

        return preg_replace('/_types$/', '', $table);
    }

    public function getDynamicTableName() {
        return 'soda_' . str_slug($this->identifier, '_');
    }

    public static function bootDynamicCreatorTrait() {
        static::creating(function ($type) {
            $type->createTable()->addFields($type->fields);
        });

        static::deleting(function ($type) {
            $type->deleteTable();
        });
    }

    protected function createTable() {
        $table = $this->getDynamicTableName();

        if (!Schema::hasTable($table)) {
            Schema::create($table, function (Blueprint $table) {
                $this->buildDynamicTable($table);
            });
        } else {
            Throw new Exception('Table ' . $table . ' already exists');
        }

        return $this;
    }

    protected function buildDynamicTable(Blueprint $table) {
        $reference_column = $this->getDynamicType() . '_id';
        $reference_table = $this->getDynamicType() . 's';
        $reference_index = 'FK_' . $this->getDynamicTableName() . '_' . $reference_column . '_' . $reference_table;

        $table->increments('id');
        $table->integer($reference_column)->unsigned()->nullable();
        $table->foreign($reference_column, $reference_index)->references('id')->on($reference_table)->onUpdate('CASCADE')->onDelete('SET NULL');
        $table->timestamps();
    }

    protected function deleteTable() {
        $table = $this->getDynamicTableName();

        if (Schema::hasTable($table)) {
            $reference_column = $this->getDynamicType() . '_id';
            $reference_table = $this->getDynamicType() . 's';
            $reference_index = 'FK_' . $this->getDynamicTableName() . '_' . $reference_column . '_' . $reference_table;

            Schema::table($table, function (Blueprint $table) use ($reference_index) {
                $table->dropForeign($reference_index);
            });

            Schema::rename($table, $table . '_deleted_' . Carbon::now()->timestamp);
        } else {
            Throw new Exception('Table ' . $table . ' does not exist');
        }

        return $this;
    }

    public function addFields($fields) {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function addField($field) {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (!Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                SodaForm::field($field)->addToModel($table)->after('id');
            });
        }

        return $this;
    }

    public function removeField($field) {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field) {
                SodaForm::field($field)->removeFromModel($table);
            });
        }

        return $this;
    }

    public function syncFields() {
        // todo
    }
}
