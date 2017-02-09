@section("field")
	<input name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="text"
		   class="form-control field_{{ $field_name }}" value="{{ $field_value }}"/>
@overwrite

@section("footer.js")
    @parent
	<script type="text/javascript">
		$(function () {
			$('#{{ $field_id }}').datetimepicker({
				{!! Soda::getFormBuilder()->buildJsParams($field_parameters['options']) !!}
			});
		});
	</script>
@overwrite
