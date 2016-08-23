<?php
namespace Soda\Cms\Components\Forms;

use Exception;
use Illuminate\Http\Request;
use Soda\Cms\Models\Field;

abstract class AbstractFormField implements FormFieldInterface {
    protected $field;
    protected $prefix;
    protected $class;
    protected $model = null;
    protected $layout = "soda::inputs.layouts.inline";
    protected $theme = "soda::inputs";

    public function setField($field) {
        if ($field instanceOf Field) {
            $this->field = $field;
        } else {
            Throw new Exception("Field must be instance of " . Field::class . " or array.");
        }

        return $this;
    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;

        return $this;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function setLayout($layout) {
        $this->layout = $layout;

        return $this;
    }

    public function getTheme() {
        return $this->theme;
    }

    public function setTheme($theme) {
        $this->theme = $theme;

        return $this;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;

        return $this;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;

        return $this;
    }

    public function getFieldLabel() {
        return $this->field->name;
    }

    public function getFieldName() {
        return $this->field->field_name;
    }

    public function getPrefixedFieldName() {
        $field_name = $this->field->field_name;

        if ($this->prefix) {
            return $this->prefix . '[' . $field_name . ']';
        }

        return $field_name;
    }

    public function getFieldDescription() {
        return $this->field->description;
    }

    public function getFieldValue() {
        $field_name = $this->field->field_name;

        if ($this->model) {
            $value = isset($this->model->$field_name) ? $this->model->$field_name : null;

            if ($value) {
                return $this->model->$field_name;
            }
        } elseif ($this->field->field_value && !old($this->field->field_name)) {
            return $this->field->field_value;
        }

        return old($this->getPrefixedFieldName());
    }

    public function saveValue(Request $request) {
        $value = $request->input($this->getPrefixedFieldName());

        return $value;
    }

    public function getFieldParameters() {
        return $this->field->field_params;
    }

    public function getDefaultParameters() {
        return [];
    }

    public function parseFieldParameters() {
        $parameters = $this->getFieldParameters();
        $default_params = $this->getDefaultParameters();

        return is_array($parameters) ? array_replace_recursive($default_params, $parameters) : $default_params;
    }

    protected function getView() {
        return $this->field->field_type;
    }

    protected function getThemedView() {
        return $this->getTheme() . "." . $this->getView();
    }

    protected function getDefaultViewParameters() {
        return [
            'layout'              => $this->getLayout(),
            'field_view'          => $this->getThemedView(),
            'prefixed_field_name' => $this->getPrefixedFieldName(),
            'field_label'         => $this->getFieldLabel(),
            'field_name'          => $this->getFieldName(),
            'field_value'         => $this->getFieldValue(),
            'field_info'          => $this->getFieldDescription(),
            'field_class'         => $this->getClass(),
            'field_parameters'    => $this->parseFieldParameters(),
        ];
    }

    protected function getViewParameters() {
        return [];
    }

    protected function parseViewParameters() {
        return array_merge($this->getDefaultViewParameters(), $this->getViewParameters());
    }

    public function render() {
        $view = view($this->getLayout(), $this->parseViewParameters())->render();

        if(env('APP_DEBUG')) {
            try {
                return $view;
            } catch (Exception $e) {
                dd($e->getMessage(), $this);
            }
        }

        return $view;
    }

    public function __toString() {
        return $this->render();
    }
}
