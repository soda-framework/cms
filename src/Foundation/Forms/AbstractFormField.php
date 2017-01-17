<?php

namespace Soda\Cms\Foundation\Forms;

use Exception;
use Soda\Cms\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

abstract class AbstractFormField implements FormFieldInterface
{
    /*
     * Unique identifier for the field
     *
     * @var string
     */
    protected $id;

    /**
     * The view used to lay out our form field.
     *
     * @var string
     */
    protected $layout;

    /**
     * The view path used to lay out our form field.
     *
     * @var string
     */
    protected $view_path;

    /**
     * The view used to display our form field.
     *
     * @var string
     */
    protected $view;

    /**
     * Prefix our form field name, to prevent collisions.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Apply a custom class to our form field HTML.
     *
     * @var string
     */
    protected $class;

    /**
     * The model used to prefill our form field.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    public function __construct()
    {
        $this->setLayout(config('soda.cms.form.default-layout'));
        $this->setViewPath(config('soda.cms.form.default-view-path'));

        $this->boot();
    }

    /**
     * Set the Field model to build our FormField off of.
     *
     * @param $field
     *
     * @return $this
     * @throws \Exception
     */
    public function setField($field)
    {
        if ($field instanceof Field) {
            $this->field = $field;
        } else {
            throw new Exception('Field must be instance of '.Field::class.' or array.');
        }

        return $this;
    }

    /**
     * Get the field prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Sets the field prefix.
     *
     * @param $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get the layout used to display the field.
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the layout used to display the field.
     *
     * @param $layout
     *
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the view used to display the field.
     *
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the view used to display the field.
     *
     * @param $view
     *
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get the view used to display the field.
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->view_path;
    }

    /**
     * Set the view used to display the field.
     *
     * @param $view_path
     *
     * @return $this
     */
    public function setViewPath($view_path)
    {
        $this->view_path = $view_path;

        return $this;
    }

    /**
     * Get the model used to prefill the field.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the model used to prefill the field.
     *
     * @param $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the class used to style the field.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the class used to style the field.
     *
     * @param $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the field label.
     *
     * @return string
     */
    public function getFieldId()
    {
        return $this->id ?: 'field_'.str_replace('.', '_', $this->getPrefixedFieldName());
    }

    /**
     * Set the id used by the field.
     *
     * @param $id
     *
     * @return $this
     */
    public function setFieldId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the field label.
     *
     * @return string
     */
    public function getFieldLabel()
    {
        return $this->field->name;
    }

    /**
     * Get the field name.
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->field->field_name;
    }

    /**
     * Build the field name with prefix applied.
     *
     * @return string
     */
    public function buildPrefixedFieldName()
    {
        $field_name = $this->field->getAttribute('field_name');
        if ($this->prefix) {
            $adjustedPrefix = str_replace('.', '[', $this->prefix);
            if (str_contains($adjustedPrefix, '[')) {
                $adjustedPrefix = $adjustedPrefix.']';
            }

            return $adjustedPrefix.'['.$field_name.']';
        }

        return $field_name;
    }

    /**
     * Get the field name with prefix applied.
     *
     * @return string
     */
    public function getPrefixedFieldName()
    {
        $field_name = $this->field->getAttribute('field_name');
        if ($this->prefix) {
            return $this->prefix.'.'.$field_name;
        }

        return $field_name;
    }

    /**
     * Get the field description.
     *
     * @return string
     */
    public function getFieldDescription()
    {
        return $this->field->description;
    }

    /**
     * Get the field value. Prefills with old input if set, then attempts
     * to prefill with model. If no inputs found, uses Field default.
     *
     * @return string
     */
    public function getFieldValue()
    {
        $field_name = $this->field->field_name;

        if (old($this->getPrefixedFieldName()) === null) {
            if ($this->model) {
                $value = isset($this->model->$field_name) ? $this->model->$field_name : null;

                if ($value !== null) {
                    return $this->model->$field_name;
                }
            }

            return $this->field->value;
        }

        return old($this->getPrefixedFieldName());
    }

    /**
     * Manipulate the field input before returning the value that should be saved.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|string
     */
    public function getSaveValue(Request $request)
    {
        $value = $request->input($this->getPrefixedFieldName());

        return $value;
    }

    /**
     * Determines how the field is saved to a model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Http\Request            $request
     *
     * @return $this
     */
    public function saveToModel(Model $model, Request $request)
    {
        $this->setModel($model);

        $model->{$this->getFieldName()} = $this->getSaveValue($request);

        return $this;
    }

    /**
     * Get the field parameters.
     *
     * @return string
     */
    public function getFieldParameters()
    {
        return $this->field->field_params;
    }

    /**
     * Get the field default parameters (merged with field parameters).
     *
     * @return string
     */
    public function getDefaultParameters()
    {
        return [];
    }

    /**
     * Merges default field parameters and additional field parameters.
     *
     * @return array
     */
    public function parseFieldParameters()
    {
        $parameters = $this->getFieldParameters();
        $default_params = $this->getDefaultParameters();

        return is_array($parameters) ? array_replace_recursive($default_params, $parameters) : $default_params;
    }

    /**
     * Renders the field for a cell in a table-view.
     *
     * @return mixed|string
     */
    public function renderForTable()
    {
        return truncate_words(strip_tags($this->getFieldValue()), 10);
    }

    /**
     * Adds a column for this field to a DynamicModel.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return $this
     */
    public function addToModel(Blueprint $table)
    {
        return $table->string($this->getFieldName());
    }

    /**
     * Removes column for this field from our DynamicModel.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return $this
     */
    public function removeFromModel(Blueprint $table)
    {
        $table->dropColumn($this->getFieldName());

        return $this;
    }

    /**
     * Render the view for the field.
     *
     * @return string
     */
    public function render()
    {
        $view = view($this->getLayout(), $this->parseViewParameters());

        // Best way I can attempt error reporting when handling errors inside of view rendering
        if (env('APP_DEBUG')) {
            try {
                return $view->render();
            } catch (Exception $e) {
                dd($e->getMessage(), $this);
            }
        }

        return $view->render();
    }

    /**
     * Render the view for the field.
     *
     * @return string
     */
    public function __toString()
    {
        if (env('APP_DEBUG')) {
            try {
                return $this->render();
            } catch (Exception $e) {
                dd($e->getMessage(), $this);
            }
        }

        return $this->render();
    }

    protected function boot()
    {
    }

    /**
     * Get the default parameters to be sent to the field view.
     *
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        return [
            'layout'              => $this->getLayout(),
            'field_view'          => $this->getViewPath().'.'.$this->getView(),
            'prefixed_field_name' => $this->buildPrefixedFieldName(),
            'field_id'            => $this->getFieldId(),
            'field_label'         => $this->getFieldLabel(),
            'field_name'          => $this->getFieldName(),
            'field_value'         => $this->getFieldValue(),
            'field_info'          => $this->getFieldDescription(),
            'field_class'         => $this->getClass(),
            'field_parameters'    => $this->parseFieldParameters(),
        ];
    }

    /**
     * Get additional view parameters to be sent to the field view.
     *
     * @return string
     */
    protected function getViewParameters()
    {
        return [];
    }

    /**
     * Merges default view parameters and additional view parameters.
     *
     * @return array
     */
    protected function parseViewParameters()
    {
        return array_merge($this->getDefaultViewParameters(), $this->getViewParameters());
    }
}
