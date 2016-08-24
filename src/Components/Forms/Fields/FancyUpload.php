<?php

namespace Soda\Cms\Components\Forms\Fields;

use Soda\Cms\Components\Forms\AbstractFormField;

class FancyUpload extends AbstractFormField {
    protected $view = "soda::inputs.fancy_upload";

    public function getDefaultParameters() {
        $field_name = $this->getFieldName();
        $field_params = $this->getFieldParameters();
        $has_media = $this->model && $this->model->getMedia($field_name)->count() > 0 ? true : false;

        $related = [];
        if ($this->model) {
            $related = [
                'related_table' => $this->model->getTable(),
                'related_id'    => $this->model->id,
            ];
        }

        $default_parameters = [
            'initialPreview'          => [],
            'initialPreviewConfig'    => [],
            'uploadExtraData'         => [
                '_token'        => csrf_token(),
                'related_field' => $field_name,
            ],
            'deleteExtraData'         => [
                '_token' => csrf_token(),
            ],
            'uploadUrl'               => route('soda.upload'),
            'deleteUrl'               => route('soda.upload.delete'),
            'allowedFileTypes'        => [
                'image',
                'audio'
            ],
            'allowedFileExtensions'   => [
                'jpg',
                'jpeg',
                'gif',
                'png'
            ],
            'uploadAsync'             => true,
            'minFileCount'            => 1,
            'maxFileCount'            => 1,
            'overwriteInitial'        => isset($field_params['maxFileCount']) && $field_params['maxFileCount'] > 1 ? 'false' : 'true',
            'autoReplace'             => isset($field_params['maxFileCount']) && $field_params['maxFileCount'] > 1 ? 'false' : 'true',
            'initialPreviewAsData'    => false, // identify if you are sending preview data only and not the raw markup
            'theme'                   => 'fa', //we want to use font awesome instead of glyphicons.
            'previewFileIcon'         => '<i class="fa fa-file"></i>',
            'previewFileIconSettings' => [
                'docx' => '<i class="fa fa-file-word-o text-primary"></i>',
                'xlsx' => '<i class="fa fa-file-excel-o text-success"></i>',
                'pptx' => '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                'jpg'  => '<i class="fa fa-file-photo-o text-warning"></i>',
                'pdf'  => '<i class="fa fa-file-pdf-o text-danger"></i>',
                'zip'  => '<i class="fa fa-file-archive-o text-muted"></i>',
            ],
        ];

        if ($has_media) {
            foreach ($this->model->getMedia($field_name) as $image) {
                $initialPreview = '<img src="' . $image->media . '" width="120">';
                $initialPreviewConfig = [
                    'caption' => '',
                    'width'   => '120px',
                    'url'     => route('soda.upload.delete'),
                    'key'     => $image->id,
                    'extra'   => [
                        '_token' => csrf_token()
                    ]
                ];

                if ($related) {
                    $initialPreviewConfig['extra'] = array_replace_recursive($initialPreviewConfig['extra'], $related);
                }

                $default_parameters['initialPreview'][] = $initialPreview;
                $default_parameters['initialPreviewConfig'][] = $initialPreviewConfig;
            }
        }

        if ($related) {
            $default_parameters['uploadExtraData'] = array_replace_recursive($default_parameters['uploadExtraData'], $related);
        }

        return $default_parameters;
    }
}
