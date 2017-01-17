<?php

namespace Soda\Cms\Components\Forms;

use Illuminate\Http\Request;

interface FormFieldInterface
{
    public function setField($field);

    public function getPrefix();

    public function setPrefix($prefix);

    public function getTheme();

    public function setTheme($theme);

    public function getModel();

    public function setModel($model);

    public function getClass();

    public function setClass($class);

    public function getFieldLabel();

    public function getFieldName();

    public function getPrefixedFieldName();

    public function getFieldDescription();

    public function getFieldValue();

    public function saveValue(Request $request);

    public function getFieldParameters();

    public function getDefaultParameters();

    public function parseFieldParameters();

    public function render();

    public function __toString();
}
