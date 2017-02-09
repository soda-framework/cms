@section("field")
    <input id="{{ $field_id }}" type="file" name='file[]' value="{{ $field_value }}" class="form-control field_{{ $field_name }}" multiple/>
@overwrite

@section("footer.js")
    @parent
    <script type="text/javascript">
        $("#{{ $field_id }}").fileinput({
            {!! Soda::getFormBuilder()->buildJsParams($field_parameters) !!}
        });
    </script>
@stop
