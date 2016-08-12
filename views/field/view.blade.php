@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Fields</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-pencil', 'title'=>$model->name?'Field: '.$model->name:'New Field'])
	<form method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	    @include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Field Name'])
		@include('soda::inputs.dropdown',['field_name'=>'field_type', 'field_value'=>$model->field_type, 'field_label'=>'Field Type', 'field_options'=>array_merge([''=>'Select Field Type'], \Soda\Models\Field::getFieldTypes())])
		@include('soda::inputs.text',['field_name'=>'value', 'field_value'=>$model->value, 'field_label'=>'Field Default Value'])
		@include('soda::inputs.text',['field_name'=>'field_name', 'field_value'=>$model->field_name, 'field_label'=>'Field Name'])
		@include('soda::inputs.textarea',['field_name'=>'description','field_value'=>$model->description, 'field_label'=>'Field Description'])
		@include('soda::inputs.code',['field_name'=>'field_params','field_value'=>$model->field_params, 'field_label'=>'Field Parameters'])
		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection