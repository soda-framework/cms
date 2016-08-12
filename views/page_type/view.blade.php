@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>Page Types</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection

@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-edit', 'title'=>$model->name?'Page Type: '.$model->name:'New Page Type'])
	<form method="POST" action='{{route('soda.'.$hint.'.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	    @include('soda::inputs.text',['field_name'=>'name', 'field_value'=>$model->name , 'field_label'=>'Page Type Name'])
		@include('soda::inputs.textarea',['field_name'=>'description','field_value'=>$model->description, 'field_label'=>'Page Type Description'])
		@include('soda::inputs.text',['field_name'=>'description','field_value'=>$model->action, 'field_label'=>'Field Action'])
		@include('soda::inputs.text',['field_name'=>'description','field_value'=>$model->action_type, 'field_label'=>'Field Action Type'])
		@include('soda::inputs.text',['field_name'=>'package','field_value'=>$model->package, 'field_label'=>'Package'])
		@include('soda::inputs.text',['field_name'=>'identifier','field_value'=>$model->identifier, 'field_label'=>'Identifier'])

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection