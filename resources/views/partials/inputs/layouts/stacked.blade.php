@section("field.label")
    @if($field_label !== null)
    <label for="{{ $field_id }}">{{ $field_label }}</label>
    @endif
@overwrite

@section("field")
@overwrite

@section("field.info")
    @if($field_info)
        <small class="text-muted">{{ $field_info }}</small>
    @endif
@overwrite

@section("field.js")
@overwrite

@if(isset($field_view) && $field_view)
    @include($field_view)
@endif

<fieldset class="form-group field_{{ $field_name }} {{ $field_class }}">
    @yield("field.label")
    @yield("field")
    @yield("field.info")
    @yield("field.js")
</fieldset>
