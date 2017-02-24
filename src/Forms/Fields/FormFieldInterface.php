<?php

namespace Soda\Cms\Forms\Fields;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

interface FormFieldInterface
{
    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setField($field);

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setLayout($layout);

    /**
     * @return string
     */
    public function getViewPath();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setViewPath($path);

    /**
     * @return string
     */
    public function getView();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setView($view);

    public function getModel();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setModel(Model $model);

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function setClass($class);

    /**
     * @return string
     */
    public function getFieldId();

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
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
     * @return \Soda\Cms\Forms\AbstractFormField
     */
    public function removeFromModel(Blueprint $table);

    /**
     * @return \Soda\Cms\Forms\AbstractFormField
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
