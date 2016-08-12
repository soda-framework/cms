<?php
	$nice_name = preg_replace("/[^A-Za-z0-9 ]/", '', $field_name);
?>
<fieldset class="form-group field_{{@$field_name}} {{@$nice_name}} {{@$field_class}} text-field">
	<label for="field_{{@$field_name}}">{{@$field_label?$field_label:$nice_name}}</label>
	<input id="field_{{@$nice_name}}" type="file" name='file[]' value="{{$field_value}}" class="form-control field_{{@$nice_name}} {{@$nice_name}}" multiple />
	@if(@$field_info)
		<small class="text-muted">{{$field_info}}</small>
	@endif
</fieldset>

<script type="application/javascript">

	$("#field_{{$nice_name}}").fileinput({
		uploadUrl:'{{route('soda.upload')}}',
		deleteUrl:'{{route('soda.upload.delete')}}',
		allowedFileTypes:['image','audio'], //TODO: this should come from validation array.
		theme:'fa',
		uploadAsync: true,
		minFileCount: {{(isset($field_parameters['minFileCount']))? $field_parameters['minFileCount'] : 1 }},
		maxFileCount: {{(isset($field_parameters['maxFileCount']))? $field_parameters['maxFileCount'] : 1 }},
		overwriteInitial: {{ isset($field_parameters['overwriteInitial']) ? $field_parameters['overwriteInitial'] : (isset($field_parameters['maxFileCount']) && $field_parameters['maxFileCount'] > 1 ? 'false' : 'true') }},
		initialPreview:[
			{{--TODO: should be able to just specify file types then add the input fields onupload.. haven't been able to make it work yet though - this will allow for automatic detection of images/audio/video by the plugin. --}}
            @if($model->getMedia($field_name)->count() > 0)
                    @foreach($model->getMedia($field_name) as $image)
                        '<img src="{{$image->media}}" width="120"><input type="hidden" value="{{$field_value}}" name="{{$field_name}}" />',
                    @endforeach
            @endif
        ],
        initialPreviewConfig: [
            @if($model->getMedia($field_name)->count() > 0)
                @foreach($model->getMedia($field_name) as $image)
                    {
                        caption: "",
                        width: "120px",
                        url:'{{ route('soda.upload.delete') }}',
                        key: {{ $image->id }},
                        extra: {
                            '_token': "{{ csrf_token() }}",
                            @if(isset($related))
                                @foreach($related as $key=>$value)
                                    {{$key}}: "{{$value}}",
                                @endforeach
                            @endif
                        }
                    },
                @endforeach
            @endif
        ],
		allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png'],	//TODO: this should come from validation array.
		initialPreviewAsData: false, // identify if you are sending preview data only and not the raw markup
		autoReplace : true,
		uploadExtraData: {
			_token:"{{csrf_token()}}",
            @if(isset($related))
                @foreach($related as $key=>$value)
                    {{$key}}: "{{$value}}",
                @endforeach
            @endif
            related_field: "{{$field_name}}"
		},
		deleteExtraData: {
			_token:"{{csrf_token()}}"
		},
		//we want to use font awesome instead of glyphicons.
		previewFileIcon: '<i class="fa fa-file"></i>',
		previewFileIconSettings: {
			'docx': '<i class="fa fa-file-word-o text-primary"></i>',
			'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
			'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
			'jpg': '<i class="fa fa-file-photo-o text-warning"></i>',
			'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
			'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
		}
	});
</script>