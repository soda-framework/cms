@extends(soda_cms_view_path('layouts.inner'))

@section('header')
	<title>List {{str_plural(ucfirst($models[0]->title))}}</title>
@endsection

@section('content')
	<h1>List {{str_plural(ucfirst($models[0]->title))}}</h1>
	<table class="table">
		<thead>
		<tr>

			@foreach($fields as $key=>$field)
				<th>{{$key}}</th>
			@endforeach
			<th>update</th>
		</tr>
		</thead>
		<tbody>
		@foreach($models as $model)
			<tr>
				@foreach($fields as $key=>$field)
					{{--TODO: additional logic in here for different field types--}}
					<td>{{ $model->{$key} }}</td>
				@endforeach
				<td>
					@if(@$type->id)
						<a href="{{route('soda.'.$models[0]->title.'.view',['id'=>$model->id, 'type'=>@$type->id])}}" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span></a>
						<a href="{{route('soda.'.$models[0]->title.'.delete',['id'=>$model->id, 'type'=>@$type->id])}}" class="btn btn-danger btn-sm"><span class="fa fa-remove"></span></a>
					@else
						<a href="{{route('soda.'.$models[0]->title.'.view',['id'=>$model->id])}}" class="btn btn-success btn-sm"><span class="fa fa-pencil"></span></a>
						<a href="{{route('soda.'.$models[0]->title.'.delete',['id'=>$model->id])}}" class="btn btn-danger btn-sm"><span class="fa fa-remove"></span></a>
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<div>
		{!! $models->links(new Illuminate\Pagination\BootstrapFourPresenter($models)) !!}
	</div>


	<a href="#" type="submit" class="btn btn-primary">Create {{ucfirst($models[0]->title)}}</a>
@endsection
