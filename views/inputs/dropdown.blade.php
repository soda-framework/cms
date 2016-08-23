@section("field")
	<select name="{{ $prefixed_field_name }}" class="form-control" id="field_{{ $field_name}}">
		@foreach($field_parameters['options'] as $key => $option)
			<option value="{{ $key }}" {{ $field_value == $key ? "selected" : "" }}>{{ $option }}</option>
		@endforeach
	</select>
@overwrite
