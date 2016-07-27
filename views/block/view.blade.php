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
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-square', 'title'=>$model->name?'Block: '.$model->name:'New Field'])
	<form method="POST" action='{{route('soda.block.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		@include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Block Name'])
		@include('soda::inputs.textarea',['field_name'=>'description', 'field_value'=>$model->description , 'field_label'=>'Block Description'])
		@include('soda::inputs.text',['field_name'=>'identifier', 'field_value'=>$model->identifier , 'field_label'=>'Block Identifier'])

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection