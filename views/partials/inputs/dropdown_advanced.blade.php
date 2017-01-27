<?php
    $values = [];
    $isArray = true;

    if($field_value instanceof Illuminate\Support\Collection)
    {
        $isArray = false;
        $field_value = $field_value->pluck($field_parameters['value_column'], $field_parameters['key_column'])->toArray();
        $selectedArrayValues = array_keys($field_value);
    } else {
        $selectedArrayValues = is_array($field_value) ? $field_value : [$field_value];
    }
?>
@section("field")
    <select name="{{ $prefixed_field_name }}{{ $field_parameters['multiple'] ? '[]' : '' }}" {{ $field_parameters['multiple'] ? 'multiple' : '' }} class="form-control" id="{{ $field_id }}">
        @foreach($field_parameters['options'] as $optGroup => $options)
            @if(is_array($options))
                <optgroup label="{{ $optGroup }}">
                    @foreach($options as $key => $option)
                        <?php $values[] = $key ?>
                        <option value="{{ $key }}" {{ $field_value === $key || in_array($key, $selectedArrayValues) ? "selected" : "" }}>{{ $option }}</option>
                    @endforeach
                </optgroup>
            @else
                <?php $values[] = $optGroup ?>
                <option value="{{ $optGroup }}" {{ $field_value === $optGroup || in_array($optGroup, $selectedArrayValues) ? "selected" : "" }}>{{ $options }}</option>
            @endif
        @endforeach
        @foreach(array_diff($selectedArrayValues, $values) as $value)
            @if($value !== null && $value !== '')
                <option value="{{ !$isArray ? $field_value[$value] : $value }}" selected>{{ $value }}</option>
            @endif
        @endforeach
    </select>

    <script>
        $(function(){
            $('#{{ $field_id }}').select2({
                tags: {{ $field_parameters['combo'] == true ? 'true' : 'false' }},
                multiple: {{ $field_parameters['multiple'] == true ? 'true' : 'false' }},
                @if($field_parameters['combo'])
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
