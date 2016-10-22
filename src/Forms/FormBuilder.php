<?php namespace Soda\Cms\Forms;

use Exception;
use Illuminate\Support\Facades\Config;
use Soda\Cms\Database\Blocks\BlockType;
use Soda\Cms\Database\Fields\Field;
use Soda\Cms\Database\Fields\Interfaces\FieldInterface;
use Soda\Cms\Database\ModelBuilder;

class FormBuilder
{
    protected $registrar;

    public function __construct(FormFieldRegistrar $registrar)
    {
        $this->registrar = $registrar;
    }

    public function getRegistrar()
    {
        return $this->registrar;
    }

    /**
     * Returns an array of registered fields, keyed by field slug,
     * values in readable format
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

    /**
     * Instantiates a new FormField from our Field model or array
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
            $field = app('soda.field.model')->fill($field);
        }

        if (!$field instanceOf FieldInterface) {
            Throw new Exception("Field must implement interface ".FieldInterface::class." or be an array.");
        }

        $formField = $this->getRegistrar()->resolve($field);

        if($prefix) {
            $formField->setPrefix($prefix);
        }

        return $formField;
    }

    /**
     * Formats a JSON string to be pasted into our Javascript
     *
     * @param $parameters
     *
     * @return string
     */
    public function buildJsParams($parameters)
    {
        if (!$parameters) return '';

        $json_parameters = json_encode($parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return trim($json_parameters, '{}');
    }

    public function __call($name, $arguments)
    {
        if ($this->getRegistrar()->isRegistered($name)) {
            $field = app('soda.field.model')->fill($arguments[0]);
            $field->setAttribute('field_type', $name);

            return $this->getRegistrar()->resolve($field);
        } else {
            throw new Exception("Field ".$name." is not registered.");
        }
    }

    /**
     * Set the default path to input layouts
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
     * Set the default path to input views
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
