@section("field.label")
    @parent
@stop

@section("field")
    <div class="input-group input-group--alt">
        <input data-slug="{{ $field_parameters['allow-external'] }}" data-slug-prefix="{{ $field_parameters['prefix'] }}" name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="text" class="form-control field_{{ $field_name }}" value="{{ $field_value }}"/>
        <span class="input-group-btn">
            <button data-slug-generate="#{{ $field_id }}" data-slug-generate-from="{{ $field_parameters['from'] }}" type="button" class="btn btn-success slug-generate">Generate</button>
            @if($field_parameters['allow-external'])
                <button data-slug-external="#{{ $field_id }}" type="button" class="btn btn-default slug-external">External link</button>
            @endif
        </span>
    </div>
@overwrite
