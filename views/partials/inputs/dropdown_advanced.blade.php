@section("field")
    <select name="{{ $prefixed_field_name }}{{ $field_parameters['multiple'] ? '[]' : '' }}" {{ $field_parameters['multiple'] ? 'multiple' : '' }} class="form-control" id="field_{{ $field_name }}">
        @foreach($field_parameters['options'] as $optGroup => $options)
            @if(is_array($options))
                <optgroup label="{{ $optGroup }}">
                    @foreach($options as $key => $option)
                        <option value="{{ $key }}" {{ $field_value == $key || (is_array($field_value) && in_array($key, $field_value)) ? "selected" : "" }}>{{ $option }}</option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optGroup }}" {{ $field_value == $optGroup || (is_array($field_value) && in_array($optGroup, $field_value)) ? "selected" : "" }}>{{ $options }}</option>
            @endif
        @endforeach
    </select>

    <script>
        $(function(){
            $('#field_{{ $field_name }}').select2({
                tags: {{ $field_parameters['multiple'] ? 'true' : 'false' }},
                createTag: function (params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    }
                },
                templateResult: function (data) {
                    var $result = $("<span></span>");

                    $result.text(data.text);

                    if (data.newOption) {
                        $result.append(" <em>(new)</em>");
                    }

                    return $result;
                },
                {!! Soda::getFormBuilder()->buildJsParams($field_parameters['settings']) !!}
            });
        });
    </script>
@overwrite
