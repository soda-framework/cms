<?php
$json = json_encode($field_value);
?>

<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field" >
	<label for="field_{{ $field_name }}">{{ $field_label }}</label>
	<div id="json_{{ $field_name }}" style="width: 100%; height: 400px;"></div>
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="hidden" value="{{ $json && $json != "null" ? $json : '{}' }}">
	@if($field_info)
		<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>

<script>
	// create the editor
	var container = $("#json_{{ $field_name }}");
	var json_field = $("#field_{{ $field_name }}");
	var json = json_field.val();
	var editor = new JSONEditor(container[0], {
		{!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
	});
	editor.setText(json);

	json_field.closest('form').on('submit', function(e) {
		json_field.val(editor.getText());
	})

	$("#json_{{ $field_name }}").on("keydown", ".jsoneditor-field, .jsoneditor-value", function() {
		if(event.keyCode == 13 || event.keyCode == 9) { // enter or tab
			event.preventDefault();
			return false;
		}
	})
</script>
