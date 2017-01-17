<?php

namespace Soda\Cms\Models;

use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class BlockType extends Model
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
        return $this->morphToMany(Field::class, 'fieldable');
    }

    public function block()
    {
        return $this->hasMany(Block::class, 'block_type_id');
    }

    /**
     * DUMMY SHIT.
     * Add a new (page) type TABLE.
     */
    public function addType($fields)
    {
        $table = 'soda_'.$this->identifier;
        try {
            if (! Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) use ($fields) {
                    $table->increments('id');

                    //TODO: field decoder and runner in here..
                    foreach ($fields as $field) {
                        $table->string($field->field_name);
                    }

                    $table->foreign('block_id')->references('id')->on('blocks')
                        ->onUpdate('cascade')->onDelete('cascade');
                    $table->timestamps();
                });
            }
        } catch (Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * adds a field to an existing type.
     */
    public function addFieldsToType($fields)
    {
        $table = 'soda_'.$this->identifier;
        try {
            foreach ($fields as $field) {
                $field_name = $field->field_name;
                if (Schema::hasColumn($table, $field_name)) {
                    //we want to alter the existing table.
                    Schema::table($table, function ($table) use ($field_name) {
                        $table->string($field_name)->change();
                    });
                } else {
                    //we want to make a new column
                    Schema::table($table, function ($table) use ($field_name) {
                        $table->string($field_name);
                    });
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }

    public function removeFieldFromType($field)
    {
        $table = 'soda_'.$this->identifier;
        $field_name = $field->field_name;

        try {
            if (Schema::hasColumn($table, $field_name)) {
                Schema::table($table, function ($table) use ($field_name) {
                    $table->dropColumn($field_name);
                });
            }
        } catch (Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }
}
