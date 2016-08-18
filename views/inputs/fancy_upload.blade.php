<?php
$has_media = $model && $model->getMedia($field_name)->count() > 0 ? true : false;
$related = [];
if ($model) {
    $related = [
        'related_table' => $model->getTable(),
        'related_id'    => $model->id,
    ];
}

$default_params = [
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
    foreach ($model->getMedia($field_name) as $image) {
        $initialPreview = '<img src="' . $image->media . '" width="120"><input type="hidden" value="' . $field_value . '" name="' . $field_name . '" />';
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

        $default_params['initialPreview'][] = $initialPreview;
        $default_params['initialPreviewConfig'][] = $initialPreviewConfig;
    }
}

if ($related) {
    $default_params['uploadExtraData'] = array_replace_recursive($default_params['uploadExtraData'], $related);
}
?>
<fieldset class="form-group field_{{ $field_name }} {{ $field_class }} text-field">
    <label for="field_{{ $field_name }}">{{ $field_label }}</label>
    <input id="field_{{ $field_name }}" type="file" name='file[]' value="{{ $field_value }}" class="form-control field_{{ $field_name }}" multiple/>
    @if(@$field_info)
        <small class="text-muted">{{ $field_info }}</small>
    @endif
</fieldset>
<script type="application/javascript">

    $("#field_{{ $field_name }}").fileinput({
        {!! Soda::getFormBuilder()->buildJsParams($field_params, $default_params) !!}
    });
</script>
