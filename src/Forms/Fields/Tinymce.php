<?php

namespace Soda\Cms\Forms\Fields;

use Soda\Cms\Forms\AbstractFormField;

class Tinymce extends AbstractFormField
{
    protected $view = 'tinymce';

    public function getDefaultParameters()
    {
        return [
            'selector'     => '#field_'.$this->getFieldName(),
            'height'       => 500,
            'plugins'      => [
                'advlist autolink lists link image charmap print hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools',
            ],
            'toolbar1'     => 'insertfile undo redo | forecolor backcolor styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            'image_advtab' => true,
            'convert_urls' => false, //prevent "http://site.com/my/directory" becoming "/my/directory" - https://www.tinymce.com/docs/configure/url-handling/
        ];
    }
}
