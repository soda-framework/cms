@extends(config('soda.hint_path').'::layouts.inner')

@section('header')

	<title>User</title>
	{{--note: non of these have anything in them anymore--}}
			<!-- JavaScripts -->
	<script src="/sodacms/sodacms/js/content.js"></script>
	<!-- Styles -->
	<link href="/sodacms/sodacms/css/content.css" rel="stylesheet">

@endsection
 --clea
@section('content')
	@include(config('soda.hint_path').'::partials.heading',['icon'=>$model->id?'fa fa-user':'fa fa-user-plus', 'title'=>$model->name?'Field: '.$model->name:'New User'])
	<form method="POST" action='{{route('soda.field.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
		@include('soda::inputs.text',['field_name'=>'username','field_value'=>$model->username, 'field_label'=>'Username'])
		@include('soda::inputs.text',['field_name'=>'email','field_value'=>$model->email, 'field_label'=>'Email'])

		@include('soda::inputs.dropdown',['field_name'=>'role_id','field_value'=>$model->role_id, 'field_label'=>'Role', 'field_options'=>array_merge(['' => 'Select Role'],\Soda\Models\Role::lists('name','id')->toArray())])

		@if($model->id)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection