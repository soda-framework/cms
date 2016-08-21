<fieldset class="form-group field_{{ $field_name }} {{ $field_class }} text-field">
    <label for="field_{{ $field_name }}">{{ $field_label }}</label>
    <input id="field_{{ $field_name }}" type="file" name='file[]' value="{{ $field_value }}" class="form-control field_{{ $field_name }}" multiple/>
    @if(@$field_info)
        <small class="text-muted">{{ $field_info }}</small>
    @endif
</fieldset>
<script type="application/javascript">

    $("#field_{{ $field_name }}").fileinput({
        {!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
    });
</script>
