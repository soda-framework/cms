<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} checkbox-field">
    <div class="checkbox">
	<label>
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="checkbox"
		   class="field_{{ $field_name }} {{ $field_name }}" value="1" {{ $field_value?"checked":"" }}/>
           {{ $field_label ? $field_label : $field_name }}
           </label>
   </div>
	@if($field_info)
	<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>
