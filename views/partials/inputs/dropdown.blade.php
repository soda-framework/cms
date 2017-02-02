@section("field")
	<select name="{{ $prefixed_field_name }}" class="form-control" id="{{ $field_id }}">
		@foreach($field_parameters['options'] as $key => $option)
			<option value="{{ $key }}" {{ (string) $field_value === (string) $key ? "selected" : "" }}>{{ $option }}</option>
		@endforeach
	</select>
@overwrite
