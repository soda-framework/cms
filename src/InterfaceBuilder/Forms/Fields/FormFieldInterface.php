<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\Database\Models\Contracts\FieldInterface;

interface FormFieldInterface
{
    /**
     * @param \Soda\Cms\Database\Models\Contracts\FieldInterface $field
     *
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setField(FieldInterface $field);

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setLayout($layout);

    /**
     * @return string
     */
    public function getViewPath();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setViewPath($path);

    /**
     * @return string
     */
    public function getView();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setView($view);

    public function getModel();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setModel(Model $model);

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setClass($class);

    /**
     * @return string
     */
    public function getFieldId();

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function setFieldId($id);

    /**
     * @return string
     */
    public function getFieldLabel();

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @return string
     */
    public function buildPrefixedFieldName();

    /**
     * @return string
     */
    public function getPrefixedFieldName();

    /**
     * @return string
     */
    public function getFieldDescription();

    public function getFieldValue();

    public function getSaveValue(Request $request);

    /**
     * @return string
     */
    public function getFieldParameters();

    public function getDefaultParameters();

    public function parseFieldParameters();

    /**
     * @return \Illuminate\Support\Fluent
     */
    public function addToModel(Blueprint $table);

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function removeFromModel(Blueprint $table);

    /**
     * @return \Soda\Cms\InterfaceBuilder\Forms\Fields\FormFieldInterface
     */
    public function saveToModel(Model $model, Request $request);

    /**
     * @return string
     */
    public function render();

    /**
     * @return string
     */
    public function __toString();
}
