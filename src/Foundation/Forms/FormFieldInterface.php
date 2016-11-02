<?php
namespace Soda\Cms\Foundation\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

interface FormFieldInterface
{
    public function setField($field);

    public function getPrefix();

    public function setPrefix($prefix);

    public function getLayout();

    public function setLayout($layout);

    public function getViewPath();

    public function setViewPath($path);

    public function getView();

    public function setView($view);

    public function getModel();

    public function setModel($model);

    public function getClass();

    public function setClass($class);

    public function getFieldId();

    public function setFieldId($id);

    public function getFieldLabel();

    public function getFieldName();

    public function getPrefixedFieldName();

    public function buildPrefixedFieldName();

    public function getFieldDescription();

    public function getFieldValue();

    public function getSaveValue(Request $request);

    public function getFieldParameters();

    public function getDefaultParameters();

    public function parseFieldParameters();

    public function addToModel(Blueprint $table);

    public function removeFromModel(Blueprint $table);

    public function saveToModel(Model $model, Request $request);

    public function render();

    public function __toString();
}
