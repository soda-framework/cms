<?php
namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Soda;
use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;

/**
 * Class ModelBuilder
 *
 * @package Soda\Cms\Models
 */
class ModelBuilder extends Model {

    public $table;
    protected static $lastTable;
    public $index_fields = [];

    //TODO:
    //something like this for dynamic relationship.
    //$user_model->roles = new BelongsToMany($role_model->newQuery(), $user_model, $pivot_table, $foreignKey, $otherKey);

    public function __construct($params = []) {
        if ($params) {
            $this->fillable = $params;//this doesn't seem to do much here - I've had to use forceFill in the controller to make this work!
        }

        $this->table = static::$lastTable;

        parent::__construct();
    }

    public function media() {
        return $this->hasMany(Media::class, 'related_id')->where('related_table', $this->getTable());
    }

    public static function fromTable($table, $params = []) {
        if (class_exists($table)) {
            return new $table($params);
        }

        $model = new static($params);

        return $model->setTable($table);
    }

    public function setTable($table) {
        $this->table = $table;
        static::$lastTable = $table;

        return $this;
    }

    public function getTable() {
        return $this->table;
    }

    public function parseField(Field $field, Request $request) {
        $field_name = $field->field_name;

        if ($field->field_type == 'datetime') {
            //TODO: Parse format through from field params?
            //TODO: Timezone parse through from field params
            if ($request->input($field_name)) {
                $this->{$field_name} = Carbon::createFromFormat('m/d/Y g:i A', $request->input($field_name));
            } else {
                //else if it's not set (or blank) do we want to run some special stuff here?
                //could and should this be moved to outside the above if statement (could cause problems with blank fields ..like checkboxes etc)
            }
        } else {
            //default, just chuck in the values.
            $this->{$field_name} = $request->input($field->field_name);
        }

        return $this;
    }

    public function getMedia($field) {
        if(!$this->media) {
            $this->load('media');
        }

        return $this->media->where('related_field', $field);
    }


    /**
     * Get a new query builder that doesn't have any global scopes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newQueryWithoutScopes() {

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


    public function newQuery() {
        $builder = $this->newQueryWithoutScopes();

        foreach ($this->getGlobalScopes() as $identifier => $scope) {
            $builder->withGlobalScope($identifier, $scope);
        }

        return $builder;
    }


    public function hasMany($related, $foreignKey = null, $localKey = null) {

        $foreignKey = $foreignKey ? : $this->getForeignKey();

        $instance = Soda::dynamicModel($related, []);

        $localKey = $localKey ? : $this->getKeyName();

        return new HasMany($instance->newQuery(), $this, $instance->getTable() . '.' . $foreignKey, $localKey);
    }


}
