<?php

namespace Soda\Cms\Models;

use Soda;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelBuilder.
 */
class ModelBuilder extends Model
{
    public $table;
    protected static $lastTable;
    protected $fillable = [];
    protected $guarded = [];
    //public $index_fields = [];

    public function __construct($params = [])
    {
        if ($params) {
            $this->fillable = array_keys($params);
        }

        $this->table = static::$lastTable;

        parent::__construct($params);
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'related_id')->where('related_table', $this->getTable());
    }

    public static function fromTable($table, $params = [])
    {
        if (class_exists($table)) {
            return new $table($params);
        }

        $model = new static($params);

        return $model->setTable($table);
    }

    public function setTable($table)
    {
        $this->table = $table;
        static::$lastTable = $table;

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function parseField(Field $field, Request $request)
    {
        $field = Soda::getFormBuilder()->field($field);

        $field->saveToModel($this, $request);

        return $this;
    }

    public function getMedia($field)
    {
        if (! $this->media) {
            $this->load('media');
        }

        return $this->media->where('related_field', $field);
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        $model = new static((array) $attributes);

        $model->exists = $exists;
        $model->setTable($this->table);

        return $model;
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
        $builder->setModel($this)->with($this->with);

        $builder->table = $this->getTable();

        return $builder;
    }

    /*
     * TODO:
     * something like this for dynamic relationship.
     * $user_model->roles = new BelongsToMany($role_model->newQuery(), $user_model, $pivot_table, $foreignKey, $otherKey);
     *
     *
     * public function hasMany($related, $foreignKey = null, $localKey = null) {
     *
     * $foreignKey = $foreignKey ? : $this->getForeignKey();
     *
     * $instance = Soda::dynamicModel($related, []);
     *
     * $localKey = $localKey ? : $this->getKeyName();
     *
     * return new HasMany($instance->newQuery(), $this, $instance->getTable() . '.' . $foreignKey, $localKey);
     * }
     */
}
