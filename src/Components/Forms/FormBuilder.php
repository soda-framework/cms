<?php
namespace Soda\Cms\Components\Forms;

use Exception;
use Request;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Field;
use Soda\Cms\Models\ModelBuilder;

class FormBuilder {
    protected $registered_fields = [];

    /**
     * Registers a new CMS form field
     *
     * @param $name
     * @param null $field_class
     */
    public function register($name, $field_class = null){
        if(new $field_class instanceof FormFieldInterface) {
            $this->registered_fields[$name] = $field_class;
        }
    }

    /**
     * Registers an array of new CMS form fields
     *
     * @param $formFields
     */
    public function registerMany($formFields) {
        foreach($formFields as $field_name => $field_class) {
            $this->register($field_name, $field_class);
        }
    }

    /**
     * Returns a list of form fields that have been registered
     *
     * @return array
     */
    public function getRegisteredFields() {
        return $this->registered_fields;
    }

    /**
     * Returns an array of registered fields, keyed by field slug,
     * values in readable format
     *
     * @return array
     */
    public function getFieldTypes() {
        return array_map(function($value) {
            $class_name = class_basename($value);
            return ucfirst(strtolower(preg_replace('/\B([A-Z])/', ' $1', $class_name)));
        }, $this->getRegisteredFields());
    }

    /**
     * Instantiates a new FormField from our Field model or array
     *
     * @param $field
     *
     * @return mixed
     * @throws \Exception
     */
    public function newField($field) {
        if (is_array($field)) {
            $field = new Field($field);
        }

        if (!$field instanceOf Field) {
            Throw new Exception("Field must be instance of " . Field::class . " or array.");
        }

        $field_types = $this->getRegisteredFields();

        if (!isset($field_types[$field->field_type])) {
            Throw new Exception("Field " . $field->field_type ." is not registered.");
        }

        $class = $field_types[$field->field_type];
        $field_class = new $class();

        return $field_class->setField($field);
    }

    /**
     * Work In Progress
     * Returns a view for an inline editable field
     *
     * @param $model
     * @param $element
     * @param $type
     *
     * @return \Illuminate\View\View
     */
    public function editable($model, $element, $type) {
        $field_value = $model->{$element};
        if (Request::get('soda_edit')) {
            $unique = uniqid();
            if ($model instanceof ModelBuilder) {
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

    /**
     * Formats a JSON string to be pasted into our Javascript
     *
     * @param $parameters
     *
     * @return string
     */
    public function buildJsParams($parameters) {
        if(!$parameters) return '';

        $json_parameters = json_encode($parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return trim($json_parameters, '{}');
    }
}
