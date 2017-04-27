@section("field")
    <textarea name="{{ $prefixed_field_name }}" id="{{ $field_id }}" class="form-control field_{{ $field_name }} {{ $field_name }}">{{ $field_value }}</textarea>
@overwrite

@section("footer.js")
    @parent
    <script>
        $(function () {
            $('#{{ $field_id }}').tinymce({
                {!! app('soda.form')->buildJsParams($field_parameters) !!}
            });
        });
    </script>
@stop
