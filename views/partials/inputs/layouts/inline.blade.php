@section("field.label")
    <label class="col-sm-2" for="field_{{ $field_name }}">{{ $field_label }}</label>
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

<fieldset class="form-group row field_{{ $field_name }} {{ $field_class }}">
    @yield("field.label")
    <div class="col-sm-10">
        @yield("field")
        @yield("field.info")
    </div>
    @yield("field.js")
</fieldset>
