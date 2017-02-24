<?php

namespace Soda\Cms\Forms;

use Exception;
use Soda\Cms\Forms\Fields\FormFieldInterface;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;

class FormFieldRegistrar
{
    protected $registeredFields = [];

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
        foreach ($formFields as $fieldName => $fieldClass) {
            $this->register($fieldName, $fieldClass);
        }
    }

    /**
     * Registers a new CMS form field.
     *
     * @param      $name
     * @param null $fieldClass
     */
    public function register($name, $fieldClass = null)
    {
        if (new $fieldClass instanceof FormFieldInterface) {
            $this->registeredFields[$name] = $fieldClass;
        }
    }

    /**
     * Returns a list of form fields that have been registered.
     *
     * @return array
     */
    public function getRegisteredFields()
    {
        return $this->registeredFields;
    }

    public function resolve(FieldInterface $field)
    {
        if (!$this->isRegistered($field->getAttribute('field_type'))) {
            throw new Exception('Field '.$field->getAttribute('field_type').' is not registered.');
        }

        $class = $this->getRegisteredFieldClass($field->getAttribute('field_type'));
        $instance = new $class;

        return $instance->setField($field);
    }

    public function isRegistered($fieldType)
    {
        return $this->getRegisteredFieldClass($fieldType) !== null;
    }

    public function getRegisteredFieldClass($field)
    {
        return isset($this->registeredFields[$field]) ? $this->registeredFields[$field] : null;
    }
}
