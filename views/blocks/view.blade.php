@extends(config('soda.hint_path').'::layouts.inner')

@section('header')
	<title>View {{ucfirst($type->name)}}</title>
@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-file-o', 'title'=>(@$model->id?'Update ':'Create ').ucfirst($type->name)])

	<form method="POST" action='{{route('soda.dyn.edit',['type'=>$type->id, 'id'=>@$model->id])}}' class="form--wrapper">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		@foreach($type->fields as $field)
			@include("soda::inputs.".$field->field_type,['field_name'=>$field->field_name, 'field_value'=>$model->{$field->field_name}, 'field_label'=>$field->name])
		@endforeach
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
@endsection