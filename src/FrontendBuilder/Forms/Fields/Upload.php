<?php

namespace Soda\Cms\FrontendBuilder\Forms\Fields;

use Illuminate\Http\Request;
use Soda\Cms\FrontendBuilder\Forms\AbstractFormField;
use Soda\Cms\Foundation\Uploads\Uploader;

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
        $parameters = $this->parseFieldParameters();

        return (new Uploader)->uploadFile($request->file($this->getPrefixedFieldName()), isset($parameters['intervention']) ? $parameters['intervention'] : [], isset($parameters['upload_sub_dir']) ? $parameters['upload_sub_dir'] : null);
    }
}
