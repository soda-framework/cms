@section("field")
	<select name="{{ $prefixed_field_name }}[]" class="form-control" id="{{ $field_id }}" multiple data-role="tagsinput">
		@foreach((array) $field_value as $value)
			<option value="{{ $value }}">{{ $value }}</option>
		@endforeach
	</select>
@overwrite
