@extends(config('soda.hint_path').'::wrappers.'.$render)

@section('wrapper-content')
	<p>{{$type->description}}</p>
	<table class="table">
		<thead>
		<tr>
			@foreach($fields as $field)
				<th>{{$field->name}}</th>
			@endforeach
			<th>update</th>
		</tr>
		</thead>
		<tbody>
		@foreach($models as $model)
			<tr>
				@foreach($fields as $field)
					{{--TODO: additional logic in here for different field types--}}

					@if($field->field_type == 'fancy_upload')
						@switch( @pathinfo($model->{$field->field_name})['extension'] )
						@case( 'jpg' )
						@case( 'png' )
						@case( 'gif' )
						@case( 'bmp' )
						@case( 'tiff' )
						<td><img src="{{ $model->{$field->field_name} }}" alt="" width="120"/></td>
						@break
						@case( 'mp3' )
						@case( 'wav' )
						@case( 'm4a' )
						<td><audio src="{{ $model->{$field->field_name} }}" alt="" width="120"/></td>
						@break
						@default
						<td>{{ truncate_words(strip_tags($model->{$field->field_name}), 10) }}</td>
						@break
						@endswitch
					@elseif($field->field_type == 'datetime')
						<td>{{ $model->{$field->field_name} }}</td>
					@else
						<td>{{ truncate_words(strip_tags($model->{$field->field_name}), 10) }}</td>
					@endif
				@endforeach
				<td>
					<a href="{{route('soda.dyn.view',['id'=>$model->id, 'type'=>$type->identifier])}}" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span></a>
					<a href="{{route('soda.dyn.delete',['id'=>$model->id, 'type'=>$type->identifier])}}" class="btn btn-danger btn-sm"><span class="fa fa-remove"></span></a>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>

	<a href="{{route('soda.dyn.view',['type'=>$type->identifier])}}" class="btn btn-primary">create</a>
@overwrite
