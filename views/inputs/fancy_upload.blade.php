<?php
	//TODO: multiple file upload, file types validation, additional options.

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

	$("#field_{{@$nice_name}}").fileinput({
		'uploadUrl':'{{route('soda.upload')}}',
		'deleteUrl':'{{route('soda.upload.delete')}}',
		'allowedFileTypes':['image','audio'],

		uploadAsync: true,
		minFileCount: 1,
		maxFileCount: 1,
		overwriteInitial: true,
		initialPreview:[
			@if($field_value)
			"<img src='{{$field_value}}' width='120' /><input type='hidden' value='{{@$field_value}}' name='{{@$field_name}}' />"
			@endif
		],
		initialPreviewConfig:[
		@if($field_value)
				{
				caption: "{{$field_value}}",
				width: "120px",
				key: 1
				}
		@endif
		],
		allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png'],
		initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
		autoReplace : true,
		uploadExtraData: {
			_token:"{{csrf_token()}}",
			field_name:"{{$field_name}}"			//we pass over the name field so we can chuck it in from the reply nicely.
		},
		deleteExtraData: {
			_token:"{{csrf_token()}}"
		}
	});
</script>



