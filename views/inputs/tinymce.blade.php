<?php
//TODO: more options, plugins, image upload.. styles
$nice_name = preg_replace("/[^A-Za-z0-9 ]/", '', $field_name);
?>
<fieldset class="form-group field_{{@$field_name}} {{@$field_name}} {{@$field_class}} text-field">
	<label for="field_{{@$field_name}}">{{@$field_label?$field_label:$field_name}}</label>
	<textarea name="{{@$field_name}}" id="field_{{@$nicename}}"
			  class="form-control field_{{@$field_name}} {{@$field_name}} {{$nice_name}}" >{{ @$field_value }}</textarea>
	@if(@$field_info)
		<small class="text-muted">{{$field_info}}</small>
	@endif
</fieldset>


<script type="application/javascript">
	$(function() {
		setTimeout(function(){
			tinymce.baseURL = "/sodacms/sodacms/components/tinymce";

			tiny = tinymce.init({
				selector: '#field_{{@$nicename}}',
				height: 500,
				plugins: [
					'advlist autolink lists link image charmap print hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'emoticons template paste textcolor colorpicker textpattern imagetools'
				],
				toolbar1: 'insertfile undo redo | forecolor backcolor styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
				image_advtab: true,
				convert_urls: false	//prevent "http://site.com/my/directory" becoming "/my/directory" - https://www.tinymce.com/docs/configure/url-handling/
			});

		}, 500);
		{{--TODO: we need to implement a proper event onloaded in here - rather than waiting for .5 seconds and hoping for the best.--}}
	});
</script>