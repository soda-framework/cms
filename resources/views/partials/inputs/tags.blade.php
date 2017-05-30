@section("field")
    <div class="select2-tags">
        <select name="{{ $prefixed_field_name }}[]" class="form-control" id="{{ $field_id }}" multiple>
            @foreach((array) $field_value as $value)
                <option value="{{ $value }}" selected>{{ $value }}</option>
            @endforeach
        </select>
    </div>
@overwrite

@section('footer.js')
    @parent
    <script>
        $(function(){
            $('#{{ $field_id }}').select2({
                tags: true,
                multiple: true,
                selectOnClose: true,
                matcher: function(searchParams, data) {
                    // This bit taken from Select2's default matcher
                    var match = $.extend(true, {}, data);

                    // Don't partial match tags, otherwise if a user has a tag 'abc' it is
                    // impossible to then create a tag 'ab'.
                    if (searchParams.term === data.text)
                        return match;
                    return null;
                },
                dropdownCssClass: 'hide',
                {!! app('soda.form')->buildJsParams($field_parameters['settings']) !!}
            });

            $('#{{ $field_id }}').on('select2:unselect', function(e){
                $(e.params.data.element).remove();
            });
        });
    </script>
@stop
