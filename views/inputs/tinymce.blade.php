<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field">
    <label for="field_{{ $field_name }}">{{ $field_label }}</label>
    <textarea name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" class="form-control field_{{ $field_name }} {{ $field_name }}">{{ $field_value }}</textarea>
    @if($field_info)
        <small class="text-muted">{{ $field_info }}</small>
    @endif
</fieldset>


<script type="application/javascript">
    $(function () {
        window.tinyMCEPreInit = {
            base: "/sodacms/sodacms/components/tinymce",
        }
        $('#field_{{ $field_name }}').tinymce({
            {!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
        });
    });
</script>
