@section("field")
	<select name="{{ $prefixed_field_name }}[]" class="form-control" id="{{ $field_id }}" multiple>
		@foreach((array) $field_value as $value)
			<option value="{{ $value }}" selected>{{ $value }}</option>
		@endforeach
	</select>
@overwrite

@section('footer.js')
    @parent
    <script>
        $(function(){
            $('#{{ $field_id }}').select2({
                tags: true,
                multiple: true,
                dropdownCssClass: 'hide'
            });
        });
    </script>
@stop
