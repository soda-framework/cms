<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Forms\Fields\Traits\HasArrayableValue;

class Relationship extends Multiselect
{
    use HasArrayableValue;
    protected $onlyQueryFrom;

    public function getDefaultParameters()
    {
        return [
            'multiple'     => false,
            'combo'        => false,
            'array-save'   => 'relationship',
            'key_column'   => 'id',
            'value_column' => 'id',
            'settings'             => [
                'placeholder'             => 'Please select...',
                'minimumResultsForSearch' => 'infinity',
                'theme'                   => 'bootstrap',
            ],
        ];
    }

    protected function parseViewParameters()
    {
        $parameters = parent::parseViewParameters();

        $parameters['field_parameters']['options'] = $this->loadRelationship();

        return $parameters;
    }

    /**
     * Loads array from database using field parameters specified.
     *
     * return @array
     */
    protected function loadRelationship()
    {
        $field_parameters = $this->parseFieldParameters();

        $query = $this->buildRelationshipQuery($field_parameters);

        if (! $query) {
            throw new Exception('Could not build query from field parameters');
        }

        if (method_exists($this, 'amendRelationshipQuery')) {
            $query = $this->amendRelationshipQuery($query);
        }

        return $this->getRelationshipArray($query);
    }

    /**
     * Builds base query for relationship, using field parameters specified.
     *
     * @param $field_parameters
     *
     * @return bool
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
     *
     * @return array
     */
    protected function getRelationshipArray($query)
    {
        $field_parameters = $this->parseFieldParameters();

        $key_column = $field_parameters['key_column'];
        $value_column = $field_parameters['value_column'];

        return $query->pluck($value_column, $key_column)->toArray();
    }
}
