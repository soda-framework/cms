@section("field.label")
    @parent
@stop

@section("field")
    <div class="input-group">
        <input data-slug="{{ $field_parameters['allow-external'] }}" data-slug-prefix="{{ $field_parameters['prefix'] }}" name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text" class="form-control field_{{ $field_name }} {{ $field_name }}" value="{{ $field_value }}"/>
        <span class="input-group-btn">
            <button data-slug-generate="#field_{{ $field_name }}" data-slug-generate-from="{{ $field_parameters['from'] }}" type="button" class="btn btn-sm btn-success slug-generate">Generate</button>
            @if($field_parameters['allow-external'])
                <button data-slug-external="#field_{{ $field_name }}" type="button" class="btn btn-sm btn-warning slug-external">External link</button>
            @endif
        </span>
    </div>
@overwrite
