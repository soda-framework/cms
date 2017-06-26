<?php

namespace Soda\Cms\InterfaceBuilder\Forms\Fields;

use Illuminate\Database\Schema\Blueprint;
use Soda\Cms\InterfaceBuilder\Forms\AbstractFormField;

class Tinymce extends AbstractFormField
{
    protected $view = 'tinymce';

    public function getDefaultParameters()
    {
        return [
            'selector'     => '#field_'.$this->getFieldName(),
            'browser_spellcheck' => true,
            'height'       => 500,
            'plugins'      => [
                'advlist autolink lists link image charmap print hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern',
            ],
            'toolbar1'     => 'insertfile undo redo | forecolor backcolor styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            'image_advtab' => true,
            'convert_urls' => false, //prevent "http://site.com/my/directory" becoming "/my/directory" - https://www.tinymce.com/docs/configure/url-handling/

            'automatic_uploads'           => true,
            'images_upload_url'           => route('soda.mce-upload'),
            'file_browser_callback_types' => 'image',
            'file_picker_types'           => 'image',
            'images_replace_blob_uris'    => true,
            'file_picker_callback'        => 'function(cb, value, meta) {
                var input = document.createElement(\'input\');
                input.setAttribute(\'type\', \'file\');
                input.setAttribute(\'accept\', \'image/*\');

                // Note: In modern browsers input[type="file"] is functional without
                // even adding it to the DOM, but that might not be the case in some older
                // or quirky browsers like IE, so you might want to add it to the DOM
                // just in case, and visually hide it. And do not forget do remove it
                // once you do not need it anymore.

                input.onchange = function() {
                    var file = this.files[0];

                    // Note: Now we need to register the blob in TinyMCEs image blob
                    // registry. In the next release this part hopefully won\'t be
                    // necessary, as we are looking to handle it internally.
                    var id = \'blobid\' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var blobInfo = blobCache.create(id, file);
                    blobCache.add(blobInfo);

                    // call the callback and populate the Title field with the file name
                    cb(blobInfo.blobUri(), { title: file.name });
                };

                input.click();
            }',
            'images_upload_handler' => 'function(blobInfo, success, failure, progress) {
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.open(\'POST\', \''.route('soda.mce-upload').'\');

                xhr.upload.onprogress = function(e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onerror = function() {
                    failure("Image upload failed due to a XHR Transport error. Code: " + xhr.status);
                };

                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure("HTTP Error: " + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json != "string") {
                        failure("Invalid JSON: " + xhr.responseText);
                        return;
                    }

                    success(json);
                };

                formData = new FormData();
                formData.append(\'file\', blobInfo.blob(), \'tinymce_file\');
                formData.append(\'_token\', \''.csrf_token().'\');

                xhr.send(formData);
            }',
        ];
    }

    /**
     * Adds a column for this field to a DynamicModel.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return Blueprint
     */
    public function addToModel(Blueprint $table)
    {
        return $table->mediumText($this->getFieldName());
    }
}
