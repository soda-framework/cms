<?php

namespace Soda\Components\Forms;

use Exception;
use Soda\Models\Field;

class FormField
{
    protected $field;
    protected $prefix;
    protected $class;
    protected $model = null;
    protected $theme = 'soda::inputs';

    public function __construct($field)
    {
        if ($field instanceof Field) {
            $this->field = $field;
        } elseif (is_array($field)) {
            $this->field = new Field($field);
        } else {
            throw new Exception('Field must be instance of '.Field::class.' or array.');
        }
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    public function render()
    {
        try {
            return view($this->theme.'.'.$this->field->field_type, [
                'prefixed_field_name' => $this->applyPrefix($this->field->field_name),
                'field_label'         => $this->field->name,
                'field_name'          => $this->field->field_name,
                'field_value'         => $this->getValue($this->field->field_name),
                'field_info'          => $this->field->description,
                'field_class'         => $this->class,
                'field_params'        => $this->field->field_params,
                'field'               => $this->field,
                'model'               => $this->model,
            ])->render();
        } catch (Exception $e) {
            dd($e->getMessage(), $this, $this->field->field_params);
        }
    }

    protected function applyPrefix($field_name)
    {
        if ($this->prefix) {
            return $this->prefix.'['.$field_name.']';
        }

        return $field_name;
    }

    protected function getValue($field_name)
    {
        if ($this->model) {
            $value = isset($this->model->$field_name) ? $this->model->$field_name : null;

            if ($value) {
                return $this->model->$field_name;
            }
        } elseif ($this->field->field_value && ! old($this->field->field_name)) {
            return $this->field->field_value;
        }

        return old($this->applyPrefix($this->field->field_name));
    }

    public function __toString()
    {
        return $this->render();
    }
}
