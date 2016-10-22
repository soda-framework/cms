@section("field")
	<select name="{{ $prefixed_field_name }}[]" class="form-control selectpicker" id="field_{{ $field_name}}" multiple title="{{ $field_parameters['placeholder'] }}" data-style="{{ $field_parameters['style'] }}" data-selected-text-format="{{ $field_parameters['selected-text-format'] }}">
		@foreach($field_parameters['options'] as $optGroup => $options)
			@if(is_array($options))
				<optgroup label="{{ $optGroup }}">
				@foreach($options as $key => $option)
					<option value="{{ $key }}" {{ in_array($key, $field_value) ? "selected" : "" }}>{{ $option }}</option>
				@endforeach
				</optgroup>
			@else
			<option value="{{ $optGroup }}" {{ in_array($optGroup, $field_value) ? "selected" : "" }}>{{ $options }}</option>
			@endif
		@endforeach
	</select>
@overwrite
