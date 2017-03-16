@section("field")
    <input id="{{ $field_id }}" type="file" name='file[]' value="{{ $field_value }}" class="form-control field_{{ $field_name }}" multiple/>
@overwrite

@section("footer.js")
    @parent
    <script type="text/javascript">
        $("#{{ $field_id }}").fileinput({
            {!! Soda::form()->buildJsParams($field_parameters) !!}
        });


        $("#{{ $field_id }}").on('filesorted', function(event, params) {
            console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
        });
    </script>
@stop
