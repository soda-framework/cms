<fieldset class="form-group field_{{@$field_name}} {{@$field_name}} {{@$field_class}} text-field">
	<label for="field_{{@$field_name}}">{{@$field_label?$field_label:$field_name}}</label>
	<input name="{{@$field_name}}" id="field_{{@$field_name}}" type="text"
		   class="form-control field_{{@$field_name}} {{@$field_name}}" value="{{ @$field_value }}"/>
	@if(@$field_info)
	<small class="text-muted">{{$field_info}}</small>
	@endif
</fieldset>