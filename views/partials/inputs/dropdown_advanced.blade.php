<?php $values = []; ?>
@section("field")
    <select name="{{ $prefixed_field_name }}{{ $field_parameters['multiple'] ? '[]' : '' }}" {{ $field_parameters['multiple'] ? 'multiple' : '' }} class="form-control" id="{{ $field_id }}">
        @foreach($field_parameters['options'] as $optGroup => $options)
            @if(is_array($options))
                <optgroup label="{{ $optGroup }}">
                    @foreach($options as $key => $option)
                        <?php $values[] = $key ?>
                        <option value="{{ $key }}" {{ $field_value == $key || (is_array($field_value) && in_array($key, $field_value)) ? "selected" : "" }}>{{ $option }}</option>
                    @endforeach
                </optgroup>
            @else
                <?php $values[] = $optGroup ?>
                <option value="{{ $optGroup }}" {{ $field_value == $optGroup || (is_array($field_value) && in_array($optGroup, $field_value)) ? "selected" : "" }}>{{ $options }}</option>
            @endif
        @endforeach
        @foreach(array_diff(is_array($field_value) ? $field_value : [$field_value], $values) as $value)
            @if($value !== null && $value !== '')
                <option value="{{ $value }}" selected>{{ $value }}</option>
            @endif
        @endforeach
    </select>

    <script>
        $(function(){
            $('#{{ $field_id }}').select2({
                @if($field_parameters['combo'])
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
                @endif
                {!! Soda::getFormBuilder()->buildJsParams($field_parameters['settings']) !!}
            });
        });
    </script>
@overwrite
