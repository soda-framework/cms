@section("field.label")
    @if($field_label !== null)
    <div class="form-group__label">
        <label for="{{ $field_id }}">{{ $field_label }}</label>
    </div>
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

<fieldset class="form-group form-group--inline field_{{ $field_name }} {{ $field_class }}">
    @yield("field.label")
    <div class="form-group__element">
        @yield("field")
        @yield("field.info")
    </div>
    @yield("field.js")
</fieldset>
