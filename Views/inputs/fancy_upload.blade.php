<input id="input-id" type="file" class="file" />

<script type="application/javascript">
	$("#input-id").fileinput({
		'uploadUrl':'{{route('soda.upload')}}',
		'deleteUrl':'{{route('soda.upload.delete')}}',
		'allowedFileTypes':['image','audio'],
		'previewFileType':'text',
		'uploadExtraData':{
			'_token':'{{csrf_token()}}'
		}
	});
</script>



