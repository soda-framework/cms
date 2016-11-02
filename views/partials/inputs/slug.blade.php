@section("field")
	<input data-slug data-slug-prefix="{{ $field_parameters['prefix'] }}" name="{{ $prefixed_field_name }}" id="{{ $field_id }}" type="text" class="form-control field_{{ $field_name }} {{ $field_name }}" value="{{ $field_value }}"/>

    <div style="margin:5px 0;">
        <button data-slug-generate="#field_{{ $field_name }}" data-slug-generate-from="{{ $field_parameters['from'] }}" type="button" class="btn btn-success slug-generate">Generate</button>
        <button data-slug-external="#field_{{ $field_name }}" type="button" class="btn btn-warning slug-external">External link</button>
    </div>
@overwrite

<script>
</script>
