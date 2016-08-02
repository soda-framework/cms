@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Block Types</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-pencil-square', 'title'=>$model->name?'Block Type: '.$model->name:'New Block Type'])
	<form method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	    @include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Block Type Name'])
		@include('soda::inputs.textarea',['field_name'=>'description','field_value'=>$model->description, 'field_label'=>'Block Type Description'])
		@include('soda::inputs.text',['field_name'=>'action','field_value'=>$model->action, 'field_label'=>'Block Action'])
		@include('soda::inputs.text',['field_name'=>'action_type','field_value'=>$model->action_type, 'field_label'=>'Block Action Type'])
		@include('soda::inputs.text',['field_name'=>'package','field_value'=>$model->package, 'field_label'=>'Package'])
		@include('soda::inputs.text',['field_name'=>'identifier','field_value'=>$model->identifier, 'field_label'=>'Identifier'])

		<h2>TODO: add fields for this block type</h2>

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection