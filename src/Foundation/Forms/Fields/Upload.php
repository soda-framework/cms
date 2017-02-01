<?php

namespace Soda\Cms\Foundation\Forms\Fields;

use Illuminate\Http\Request;
use Soda\Cms\Foundation\Uploader;
use Soda\Cms\Foundation\Forms\AbstractFormField;

class Upload extends AbstractFormField
{
    protected $view = 'upload';

    /**
     * Manipulate the field input before returning the value that should be saved.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|string
     */
    public function getSaveValue(Request $request)
    {
        return (new Uploader)->uploadFile($request->file($this->getPrefixedFieldName()));
    }
}
