@section("field")
	<div id="field_{{ $field_name}}">
		@foreach($field_parameters['options'] as $key => $option)
			<div class="radio-inline">
				<label>
					<input type="radio" name="{{ $prefixed_field_name }}"  value="{{ $key }}" {{ $field_value == $key ? "checked" : "" }}> {{ $option }}
				</label>
			</div>
		@endforeach
	</div>
@overwrite

