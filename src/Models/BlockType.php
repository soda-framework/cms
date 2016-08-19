<?php

namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Soda\Cms\Models\Scopes\FromApplicationScope;

class BlockType extends Model {
    protected $table = 'block_types';
    protected $fillable = [
        'name',
        'description',
        'application_users_id',
        'application_id',
        'action',
        'action_type',
        'package',
        'status_id',
        'identifier',
        'edit_action',
        'edit_action_type',
        'application_user_id',
    ];

    public static function boot() {
        parent::boot();
        static::addGlobalScope(new FromApplicationScope);
    }

    public function fields() {
        return $this->morphToMany(Field::class, 'fieldable');
    }

    public function block() {
        return $this->hasMany(Block::class, 'block_type_id');
    }


    /**
     * DUMMY SHIT.
     * Add a new (page) type TABLE
     */
    public function addType($fields) {
        try {
            if (!Schema::hasTable('soda_' . $this->identifier)) {
                Schema::create('soda_' . $this->identifier, function (Blueprint $table) use ($fields) {
                    $table->increments('id'); //should this always be there?
                    //TODO: field decoder and runner in here..
                    foreach ($fields as $field) {
                        $table->string($field->field_name);
                    }
                    $table->timestamps();
                });
            }
        } catch (\Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * adds a field to an existing type.
     */
    public function addFieldsToType($fields) {
        try {
            foreach ($fields as $field) {
                if (Schema::hasColumn('soda_' . $this->identifier, $field->field_name)) {
                    //we want to alter the existing table.
                    Schema::table('soda_' . $this->identifier, function ($table) use ($field) {
                        $table->string($field->field_name)->change();
                    });
                } else {
                    //we want to make a new column
                    Schema::table('soda_' . $this->identifier, function ($table) use ($field) {
                        $table->string($field->field_name);
                    });
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }

    public static function removeFieldFromType() {
        try {
            if (Schema::hasColumn('soda_flights', '')) {
                //remove this column.
                Schema::table('soda_flights', function ($table) {
                    $table->dropColumn('airline');
                });
            }
        } catch (\Exception $e) {
            dd($e->getMessage());

            return false;
        }

        return true;
    }

}
