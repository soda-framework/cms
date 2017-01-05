@section("field")
	<div>
		@if($field_parameters['unchecked-value'] !== '' && $field_parameters['unchecked-value'] !== null)
			<input name="{{ $prefixed_field_name }}" type="hidden" value="{{ $field_parameters['unchecked-value'] }}">
		@endif
		<input name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="checkbox" class="field_{{ $field_name }} {{ $field_name }}" value="{{ $field_parameters['checked-value'] }}" {{ $field_value == $field_parameters['checked-value'] ? "checked" : "" }}>
	</div>
@overwrite
