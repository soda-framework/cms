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
					<td>{{ $model->{$field->field_name} }}</td>
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