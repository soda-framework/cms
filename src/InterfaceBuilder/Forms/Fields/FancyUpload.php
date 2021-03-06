<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Uploads\Uploader;
use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class FancyUpload extends AbstractFormField
{
    protected $view = 'fancy_upload';

    public function getDefaultParameters()
    {
        $field_name = $this->getFieldName();
        $field_params = $this->getFieldParameters();
        $is_multi = isset($field_params['maxFileCount']) && $field_params['maxFileCount'] > 1 ? true : false;
        $has_media = $this->model && (($is_multi && $this->model->getMedia($field_name)->count() > 0) || ! $is_multi && $this->model && isset($this->model->$field_name) && $this->model->$field_name) ? true : false;

        $related = [];
        if ($this->model) {
            $related = [
                'related_table' => $this->model->getTable(),
                'related_field' => $field_name,
                'related_id'    => $this->model->id,
            ];
        }

        $default_parameters = [
            'initialPreview'          => [],
            'initialPreviewConfig'    => [],
            'uploadExtraData'         => [
                'related_field' => $field_name,
                'multi'         => $is_multi ? true : false,
                'intervention'  => isset($field_params['intervention']) ? json_encode($field_params['intervention']) : '',
                'uploadSubDir'  => isset($field_params['uploadSubDir']) ? $field_params['uploadSubDir'] : '',
            ],
            'uploadUrl'               => route('soda.upload'),
            'deleteUrl'               => route('soda.upload.delete'),
            'allowedFileTypes'        => [
                'image',
                'audio',
            ],
            'allowedFileExtensions'   => [
                'jpg',
                'jpeg',
                'gif',
                'png',
            ],
            'uploadAsync'             => true,
            'minFileCount'            => 0,
            'maxFileCount'            => 1,
            'overwriteInitial'        => $is_multi ? false : true,
            'autoReplace'             => $is_multi ? false : true,
            'initialPreviewAsData'    => true, // identify if you are sending preview data only and not the raw markup
            'theme'                   => 'explorer', //we want to use font awesome instead of glyphicons.
            'allowedPreviewTypes'     => ['image', 'pdf'],
            'previewFileIcon'         => '<i class="fa fa-file"></i>',
            'validateInitialCount'    => true,
            'previewFileIconSettings' => [
                'docx' => '<i class="fa fa-file-word-o text-primary"></i>',
                'xlsx' => '<i class="fa fa-file-excel-o text-success"></i>',
                'pptx' => '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                'jpg'  => '<i class="fa fa-file-photo-o text-warning"></i>',
                'pdf'  => '<i class="fa fa-file-pdf-o text-danger"></i>',
                'zip'  => '<i class="fa fa-file-archive-o text-muted"></i>',
            ],
        ];

        if ($is_multi && $has_media) {
            foreach ($this->model->getMedia($field_name) as $image) {
                $mimeType = Uploader::detectByFilename($image->media);
                $initialPreview = $image->media;
                $initialPreviewConfig = [
                    'caption'  => '',
                    'filetype' => $mimeType,
                    'type'     => Uploader::guessFileTypeByMimeType($mimeType),
                    'width'    => '120px',
                    'url'      => route('soda.upload.delete'),
                    'key'      => $image->id,
                    'extra'    => [],
                ];

                if ($related) {
                    $initialPreviewConfig['extra'] = array_replace_recursive($initialPreviewConfig['extra'], $related);
                }

                $default_parameters['initialPreview'][] = $initialPreview;
                $default_parameters['initialPreviewConfig'][] = $initialPreviewConfig;
            }
        } elseif (! $is_multi && $has_media) {
            $mimeType = Uploader::detectByFilename($this->model->$field_name);
            $initialPreview = $this->model->$field_name;
            $initialPreviewConfig = [
                'caption'  => '',
                'filetype' => $mimeType,
                'type'     => Uploader::guessFileTypeByMimeType($mimeType),
                'width'    => '120px',
                'url'      => route('soda.upload.delete'),
                'extra'    => [],
            ];

            if ($related) {
                $initialPreviewConfig['extra'] = array_replace_recursive($initialPreviewConfig['extra'], $related);
            }

            $default_parameters['initialPreview'][] = $initialPreview;
            $default_parameters['initialPreviewConfig'][] = $initialPreviewConfig;
        }

        if ($related) {
            $default_parameters['uploadExtraData'] = array_replace_recursive($default_parameters['uploadExtraData'], $related);
        }

        return $default_parameters;
    }

    public function getView()
    {
        if ($this->model === null || $this->model->id === null) {
            $static = new StaticText;

            return $static->getView();
        }

        return parent::getView();
    }

    public function renderForTable()
    {
        $parameters = $this->parseFieldParameters();
        $isMulti = isset($parameters['maxFileCount']) && $parameters['maxFileCount'] > 1 ? true : false;
        $fileName = $this->getFieldValue();
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (! $fileName) {
            return '';
        }

        if (! $isMulti) {
            switch ($fileExtension) {
                case 'jpg':
                case 'png':
                case 'gif':
                case 'bmp':
                case 'tiff':
                    return '<img src="'.$fileName.'" alt="" width="120"/>';
                case 'mp3':
                case 'wav':
                case 'm4a':
                    return '<audio src="'.$fileName.'" alt="" width="120"/>';
                case 'pdf':
                    return '<object data="'.$fileName.'" type="application/pdf" width="120"><embed src="'.$fileName.'" width="120" type="application/pdf"></object>';
            }

            return '<a href="'.$fileName.'" target="_blank">View File</a>';
        }

        return 'Multiple files';
    }

    public function getFieldValue()
    {
        if ($this->model === null || $this->model->id === null) {
            return 'Please save before uploading a file.';
        }

        return parent::getFieldValue();
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
        $parameters = $this->parseFieldParameters();
        $isMulti = isset($parameters['maxFileCount']) && $parameters['maxFileCount'] > 1 ? true : false;

        if (! $isMulti) {
            return parent::saveToModel($model, $request);
        }

        return $this;
    }
}
