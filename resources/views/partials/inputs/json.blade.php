@section("field")
    <div id="json_{{ $field_id }}" class="field_{{ $field_name }}" style="width: 100%; height: 400px;"></div>
    <input name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="hidden" value="{{ $field_value }}">
@overwrite

@section("footer.js")
    @parent
	<script>
		// create the editor
		var container_{{ $field_id }} = $("#json_{{ $field_id }}");
		var json_field_{{ $field_id }} = $("#{{ $field_id }}");
		var json_{{ $field_id }} = json_field_{{ $field_id }}.val();
		var editor_{{ $field_id }} = new JSONEditor(container_{{ $field_id }}[0], {
			{!! app('soda.form')->buildJsParams($field_parameters) !!}
		});
		editor_{{ $field_id }}.setText(json_{{ $field_id }});

		json_field_{{ $field_id }}.closest('form').on('submit', function(e) {
			json_field_{{ $field_id }}.val(editor_{{ $field_id }}.getText());
		})

		$("#json_{{ $field_name }}").on("keydown", ".jsoneditor-field, .jsoneditor-value", function() {
			if(event.keyCode == 13 || event.keyCode == 9) { // enter or tab
				event.preventDefault();
				return false;
			}
		})
	</script>
@stop
