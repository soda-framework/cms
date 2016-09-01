@section("field")
    <textarea name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" class="form-control field_{{ $field_name }} {{ $field_name }}">{{ $field_value }}</textarea>
@overwrite

@section("field.js")
    <script type="application/javascript">
        $(function () {
            window.tinyMCEPreInit = {
                base: "/soda/cms/components/tinymce",
            }
            $('#field_{{ $field_name }}').tinymce({
                {!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
            });
        });
    </script>
@overwrite
