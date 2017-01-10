@section("field")
	<div id="json_{{ $field_id }}" class="field_{{ $field_name }}" style="width: 100%; height: 400px;"></div>
	<input name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="hidden" value="{{ $field_value }}">
@overwrite

@section("field.js")
	<script>
		// create the editor
		var container = $("#json_{{ $field_id }}");
		var json_field = $("#{{ $field_id }}");
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
@overwrite
