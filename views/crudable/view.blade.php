@extends(config('soda.hint_path').'::layouts.inner')

@section('header')
	<title>View {{ucfirst($model->name)}}</title>
@endsection

@section('content')
	<h1>
		@if(!@$model->id)
			Create {{ucfirst($model->name)}}
		@else
			Update {{ucfirst($model->name)}}
		@endif
	</h1>

	<form method="POST" action='{{route('soda.block.edit',['id'=>@$model->id])}}' class="form-wrapper">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		{{--TODO: swap for @each?? --}}

		@foreach($fields as $field)
			@include("soda::inputs.".$field['type'],['field_name'=>$field['name'], 'field_value'=>$model->{$field['name']}, 'field_label'=>$field['name']])
		@endforeach
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
@endsection
