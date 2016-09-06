@section("field")


	<div class="toggle-switch">
		<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="checkbox" class="field_{{ $field_name }} {{ $field_name }}" value="{{ $field_parameters['on-value'] }}" {{ $field_value == $field_parameters['on-value'] ? "checked" : "" }}>
		<label for="field_{{ $field_name }}"></label>
	</div>

@overwrite
