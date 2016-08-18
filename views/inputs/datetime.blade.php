{{-- TODO: need options in here --}}
{{-- TODO: date formats --}}
<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field">
	<label for="field_{{ $field_name }}">{{ $field_label }}</label>
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text"
		   class="form-control field_{{ $field_name }} {{ $field_name }}" value="{{ $field_value ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $field_value)->format('m/d/Y g:i A') : '' }}"/>
	@if($field_info)
		<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>

<script type="text/javascript">
	$(function () {
		$('#field_{{ $field_name }}').datetimepicker({
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-caret-up",
				down: "fa fa-caret-down",
				previous: "fa fa-caret-left",
				next: "fa fa-caret-right"
			}
		});
	});
</script>
