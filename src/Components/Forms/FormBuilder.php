<?php
namespace Soda\Components\Forms;

use Request;
use Soda\Models\BlockType;

class FormBuilder {
    protected $field_types = [
        'checkbox'     => 'checkbox',
        'datetime'     => 'datetime',
        'dropdown'     => 'dropdown',
        'fancy_upload' => 'fancy_upload',
        'media_upload' => 'media_upload',
        'lat_lon'      => 'lat_lon',
        'password'     => 'password',
        'static'       => 'static',
        'text'         => 'text',
        'textarea'     => 'textarea',
        'tinymce'      => 'tinymce',
        'upload'       => 'upload',
    ];

    public function newField($field) {
        return new FormField($field);
    }

    public function getFieldTypes() {
        return $this->field_types;
    }

    public function editable($model, $element, $type) {
        $field_value = $model->{$element};
        if (Request::get('soda_edit')) {
            $unique = uniqid();
            if (get_class($model) == 'Soda\Models\ModelBuilder') {
                //we need to get the db name and attach to the field..
                $type = BlockType::where('identifier', $type)->first();
                $link = route('soda.dyn.inline.edit', [
                    'type'  => $type->identifier,
                    'model' => $model->id,
                    'field' => $element,
                ]);
            }

            //TODO: figure out which type of field we need to use here..
            return view('soda::inputs.inline.text', [
                'link'        => $link,
                'element'     => $element,
                'model'       => $model,
                'unique'      => $unique,
                'field_value' => $field_value,
            ]);
        } else {
            return $field_value;
        }
    }
}
