@section("field")
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text"
		   class="form-control field_{{ $field_name }} {{ $field_name }}" value="{{ $field_value }}"/>
@overwrite

@section("field.js")
	<script type="text/javascript">
		$(function () {
			$('#field_{{ $field_name }}').datetimepicker({
				{!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
			});
		});
	</script>
@overwrite
