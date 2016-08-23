@section("field")
    <input id="field_{{ $field_name }}" type="file" name='file[]' value="{{ $field_value }}" class="form-control field_{{ $field_name }}" multiple/>
@overwrite

@section("field.js")
    <script type="text/javascript">
        $("#field_{{ $field_name }}").fileinput({
            {!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
        });
    </script>
@overwrite
