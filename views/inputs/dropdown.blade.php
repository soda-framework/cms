<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} dropdown-field">
	<label for="field_{{ $field_name }}">{{ $field_label ? $field_label : $field_name}}</label>

	<select name="{{ $prefixed_field_name }}" class="form-control" id="field_{{ $field_name}}">
		@foreach($field_params['options'] as $key => $option)
			<option value="{{ $key }}" {{ $field_value == $key ? "selected" : "" }}>{{ $option }}</option>
		@endforeach
	</select>
	@if($field_info)
		<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>
