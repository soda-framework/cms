<?php

namespace Soda\Cms\Components\Forms;

use Exception;
use Soda\Cms\Models\Field;

class FormFieldRegistrar
{
    protected $registered_fields = [];

    public function __construct($fields = [])
    {
        if ($fields) {
            $this->registerMany($fields);
        }
    }

    /**
     * Registers an array of new CMS form fields.
     *
     * @param $formFields
     */
    public function registerMany($formFields)
    {
        foreach ($formFields as $field_name => $field_class) {
            $this->register($field_name, $field_class);
        }
    }

    /**
     * Registers a new CMS form field.
     *
     * @param      $name
     * @param null $field_class
     */
    public function register($name, $field_class = null)
    {
        if (new $field_class instanceof FormFieldInterface) {
            $this->registered_fields[$name] = $field_class;
        }
    }

    /**
     * Returns a list of form fields that have been registered.
     *
     * @return array
     */
    public function getRegisteredFields()
    {
        return $this->registered_fields;
    }

    public function resolve(Field $field)
    {
        if (! $this->isRegistered($field->field_type)) {
            throw new Exception('Field '.$field->field_type.' is not registered.');
        }

        $class = $this->getRegisteredFieldClass($field->field_type);
        $instance = new $class;

        return $instance->setField($field);
    }

    public function isRegistered($field_type)
    {
        return $this->getRegisteredFieldClass($field_type) !== null;
    }

    public function getRegisteredFieldClass($field)
    {
        return isset($this->registered_fields[$field]) ? $this->registered_fields[$field] : null;
    }
}
