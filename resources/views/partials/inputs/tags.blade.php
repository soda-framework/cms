@section("field")
	<select name="{{ $prefixed_field_name }}[]" class="form-control" id="field_{{ $field_name}}" multiple data-role="tagsinput">
		@foreach((array) $field_value as $value)
			<option value="{{ $value }}">{{ $value }}</option>
		@endforeach
	</select>
@overwrite
