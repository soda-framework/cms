<?php
namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelBuilder
 * @package Soda\Models
 */
class ModelBuilder extends Model
{

    protected static $_table;

    public $index_fields = [];

    //TODO:
    //something like this for dynamic relationship.
    //$user_model->roles = new BelongsToMany($role_model->newQuery(), $user_model, $pivot_table, $foreignKey, $otherKey);


    public function __construct($parms = null)
    {
        parent::__construct();
        if ($parms) {
            //this doesn't seem to do much here - I've had to use forceFill in the controller to make this work!
            $this->fillable = $parms;
        }

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
//		dd($ret, \Request::input());
        return $ret;
    }

    public function setTable($table)
    {
        static::$_table = $table;
    }

    public function getTable()
    {
        return static::$_table;
    }
}