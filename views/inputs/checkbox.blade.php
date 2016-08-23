@section("field.label")
@overwrite

@section("field")
    <div class="checkbox">
		<label>
			<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="checkbox" class="field_{{ $field_name }} {{ $field_name }}" value="1" {{ $field_value ? "checked" : "" }}/>
			{{ $field_label }}
		</label>
	</div>
@overwrite
