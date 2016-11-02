<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use DB;
use Soda\Cms\Foundation\Forms\Fields\Traits\HasArrayableValue;

class RelationshipGroup extends Relationship
{
    use HasArrayableValue;
    protected $view = "dropdown_advanced";

    public function getDefaultParameters()
    {
        return [
            'options'              => $this->loadRelationship(),
            'multiple'             => false,
            'combo'                => false,
            'array-save'           => 'json',
            'style'                => 'btn-dropdown',
            'selected-text-format' => 'count > 3',
            'placeholder'          => 'Please select...',
            'key_column'           => 'id',
            'value_column'         => 'id',
            'group_column'         => null,
        ];
    }

    /**
     * Pulls array from query, using field parameters specified
     *
     * @param $query
     * @param $field_parameters
     *
     * @return array
     */
    protected function getRelationshipArray($query, $field_parameters)
    {
        $key_column = isset($field_parameters['key_column']) ? $field_parameters['key_column'] : 'id';
        $value_column = isset($field_parameters['value_column']) ? $field_parameters['value_column'] : $key_column;
        $group = $field_parameters['group_column'];

        return $query->get([$group, $value_column, $key_column])->groupBy($group)->transform(function ($item) use ($value_column, $key_column) {
            return $item->pluck($value_column, $key_column);
        })->toArray();
    }
}
