<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Dropdown
{
    protected $onlyQueryFrom;
    protected $relationshipData;

    public function getDefaultParameters()
    {
        return [
            'options' => [],
        ];
    }

    /**
     * Merges default field parameters and additional field parameters.
     *
     * @return array
     */
    public function parseFieldParameters()
    {
        $parameters = parent::parseFieldParameters();

        if (! isset($parameters['options']) || ! is_array($parameters['options']) || count($parameters['options']) == 0) {
            $parameters['options'] = $this->loadRelationship();
        }

        return $parameters;
    }

    /**
     * Loads array from database using field parameters specified.
     *
     * return @array
     */
    protected function loadRelationship()
    {
        $field_parameters = $this->getFieldParameters();

        $query = $this->buildRelationshipQuery($field_parameters);

        if (! $query) {
            throw new Exception('Could not build query from field parameters');
        }

        if (method_exists($this, 'amendRelationshipQuery')) {
            $query = $this->amendRelationshipQuery($query);
        }

        return $this->getRelationshipArray($query, $field_parameters);
    }

    /**
     * Builds base query for relationship, using field parameters specified.
     *
     * @param string $field_parameters
     *
     * @return \Illuminate\Database\Query\Builder
     * @throws \Exception
     */
    protected function buildRelationshipQuery($field_parameters)
    {
        if (isset($field_parameters['table']) && ! $this->onlyQueryFrom == 'model') {
            $table = $field_parameters['table'];

            return DB::table($table);
        } elseif (isset($field_parameters['model']) && ! $this->onlyQueryFrom == 'table') {
            $class = $field_parameters['model'];
            $model = new $class;

            if ($model instanceof Model) {
                return $model;
            }

            throw new Exception('Class \''.$class.'\' must be an isntance of \''.Model::class.'\'');
        }

        throw new Exception('No valid query to build from field parameters');
    }

    /**
     * Pulls array from query, using field parameters specified.
     *
     * @param $query
     * @param string $field_parameters
     *
     * @return array
     */
    protected function getRelationshipArray($query, $field_parameters)
    {
        // Relationship data is stored to prevent re-querying
        if (! $this->relationshipData) {
            $key_column = isset($field_parameters['key_column']) ? $field_parameters['key_column'] : 'id';
            $value_column = isset($field_parameters['value_column']) ? $field_parameters['value_column'] : $key_column;

            $this->relationshipData = $query->pluck($value_column, $key_column)->toArray();
        }

        return $this->relationshipData;
    }
}
