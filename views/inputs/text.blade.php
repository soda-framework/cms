<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field">
	<label for="field_{{ $field_name }}">{{ $field_label }}</label>
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text" class="form-control field_{{ $field_name}} {{ $field_name }}" value="{{ $field_value }}"/>
	@if($field_info)
	<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>
