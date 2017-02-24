<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\Fields\Traits\HasArrayableValue;

class RelationshipGroup extends Relationship
{
    use HasArrayableValue;
    protected $view = 'dropdown_advanced';

    public function getDefaultParameters()
    {
        return [
            'options'              => [],
            'key_column'           => 'id',
            'value_column'         => 'id',
            'group_column'         => null,

            'multiple'             => true,
            'combo'                => false,
            'array-save'           => 'json',
            'settings'             => [
                'placeholder'             => 'Please select...',
                'minimumResultsForSearch' => 'infinity',
                'theme'                   => 'bootstrap',
            ],
        ];
    }

    /**
     * Pulls array from query, using field parameters specified.
     *
     * @param $query
     * @param $field_parameters
     *
     * @return array
     */
    protected function getRelationshipArray($query, $field_parameters)
    {
        // Relationship data is stored to prevent re-querying
        if (!$this->relationshipData) {
            $key_column = isset($field_parameters['key_column']) ? $field_parameters['key_column'] : 'id';
            $value_column = isset($field_parameters['value_column']) ? $field_parameters['value_column'] : $key_column;
            $group = $field_parameters['group_column'];

            $this->relationshipData = $query->get([$group, $value_column, $key_column])->groupBy($group)->transform(function($item) use ($value_column, $key_column) {
                return $item->pluck($value_column, $key_column);
            })->toArray();
        }

        return $this->relationshipData;
    }
}
