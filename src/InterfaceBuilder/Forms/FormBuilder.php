<?php

namespace Soda\Cms\InterfaceBuilder\Forms;

use Exception;
use Soda\Cms\Database\Models\Field;
use Illuminate\Support\Facades\Config;
use Soda\Cms\Database\Models\Contracts\FieldInterface;

class FormBuilder
{
    protected $registrar;

    public function __construct(FormFieldRegistrar $registrar)
    {
        $this->registrar = $registrar;
    }

    /**
     * Returns an array of registered fields, keyed by field slug,
     * values in readable format.
     *
     * @return array
     */
    public function getFieldTypes()
    {
        return array_map(function ($value) {
            $class_name = class_basename($value);

            return ucfirst(strtolower(preg_replace('/\B([A-Z])/', ' $1', $class_name)));
        }, $this->getRegistrar()->getRegisteredFields());
    }

    public function getRegistrar()
    {
        return $this->registrar;
    }

    /**
     * Instantiates a new FormField from our Field model or array.
     *
     * @param      $field
     *
     * @param null $prefix
     *
     * @return mixed
     * @throws Exception
     */
    public function field($field, $prefix = null)
    {
        if (is_array($field)) {
            $field = new Field($field);
        }

        if (! $field instanceof FieldInterface) {
            throw new Exception('Field must implement interface '.FieldInterface::class.' or be an array.');
        }

        $formField = $this->getRegistrar()->resolve($field);

        if ($prefix) {
            $formField->setPrefix($prefix);
        }

        return $formField;
    }

    /**
     * Formats a JSON string to be pasted into our Javascript.
     *
     * @param $parameters
     *
     * @return string
     */
    public function buildJsParams($parameters)
    {
        if (! $parameters) {
            return '';
        }

        $extractedParams = $this->extractJsFunctions($parameters);

        $json_parameters = json_encode($extractedParams['parameters'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $json_parameters = str_replace($extractedParams['replacements'], $extractedParams['values'], $json_parameters);

        return trim($json_parameters, '{}');
    }

    protected function extractJsFunctions($parameters)
    {
        $valueArr = [];
        $replaceKeys = [];

        foreach ($parameters as $key => &$value) {
            if (is_string($value)) {
                // Look for values starting with 'function('
                if (strpos($value, 'function(') === 0 || strpos($value, 'function (') === 0) {
                    // Store function string.
                    $valueArr[] = $value;
                    // Replace function string in $foo with a ‘unique’ special key.
                    $value = "%$key%";
                    // Later on, we’ll look for the value, and replace it.
                    $replaceKeys[] = "\"$value\"";
                }
            } elseif (is_array($value)) {
                $extract = $this->extractJsFunctions($value);
                $valueArr = array_merge($valueArr, $extract['values']);
                $replaceKeys = array_merge($replaceKeys, $extract['replacements']);

                $value = $extract['parameters'];
            }
        }

        return ['values' => $valueArr, 'replacements' => $replaceKeys, 'parameters' => $parameters];
    }

    public function __call($name, $arguments)
    {
        if ($this->getRegistrar()->isRegistered($name)) {
            $field = $arguments[0];

            if (! $field instanceof Field) {
                $field = new Field($field);
                $field->setAttribute('field_type', $name);
            }

            return $this->getRegistrar()->resolve($field);
        } else {
            throw new Exception('Field '.$name.' is not registered.');
        }
    }

    /**
     * Set the default path to input layouts.
     *
     * @param $layout
     *
     * @return $this
     */
    public function setDefaultLayout($layout)
    {
        Config::set('soda.cms.form.default-layout', $layout);

        return $this;
    }

    /**
     * Set the default path to input views.
     *
     * @param $view_path
     *
     * @return $this
     */
    public function setDefaultViewPath($view_path)
    {
        Config::set('soda.cms.form.default-view-path', $view_path);

        return $this;
    }
}
