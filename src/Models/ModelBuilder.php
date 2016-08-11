<?php
namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Soda\Models\Media;
use Soda;

/**
 * Class ModelBuilder
 * @package Soda\Models
 */
class ModelBuilder extends Model
{

    public $table;
    protected static $_table;

    protected static $lastTable;
    public $index_fields = [];

    //TODO:
    //something like this for dynamic relationship.
    //$user_model->roles = new BelongsToMany($role_model->newQuery(), $user_model, $pivot_table, $foreignKey, $otherKey);



    public function __construct($parms = null)
    {
        if ($parms) {
            //this doesn't seem to do much here - I've had to use forceFill in the controller to make this work!
            $this->fillable = $parms;
        }

        $this->table = static::$_table;

        parent::__construct();
    }

    public static function fromTable($table, $parms = [])
    {

        $ret = null;
        if (class_exists($table)) {
            $ret = new $table($parms);
        } else {
            $ret = new static($parms);
            $ret->setTable($table);
        }

        return $ret;
    }

    public function setTable($table)
    {
        static::$_table = $table;
        $this->table = $table;
        static::$lastTable = $table;

    }

    public function getTable()
    {
        return $this->table;
    }


    //Not sure if this is the right place to do this
    public function getMedia($field, $variant = 'original', $callback = null){
        return Media::where('related_table', $this->getTable())->where('related_id', $this->id)
            ->where('related_field', $field)->get();
    }



    /**
     * Get a new query builder that doesn't have any global scopes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newQueryWithoutScopes()
    {

        $builder = $this->newEloquentBuilder(
            $this->newBaseQueryBuilder()
        );


        // Once we have the query builders, we will set the model instances so the
        // builder can easily access any information it may need from the model
        // while it is constructing and executing various queries against it.
        $new = $builder->setModel($this)->with($this->with);

        $new->table = $this->getTable();

        return $new;
    }



    public function newQuery()
    {
        $builder = $this->newQueryWithoutScopes();

        foreach ($this->getGlobalScopes() as $identifier => $scope) {
            $builder->withGlobalScope($identifier, $scope);
        }

        return $builder;
    }



    public function hasMany($related, $foreignKey = null, $localKey = null)
    {

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = Soda::dynamicModel($related, []);


        $localKey = $localKey ?: $this->getKeyName();

        return new HasMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }


}
