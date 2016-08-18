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

		{!! Soda::field([
            'name'        => 'Field Name',
            'field_type'  => 'text',
            'field_name'  => 'name',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'         => 'Field Type',
            'field_type'   => 'dropdown',
            'field_name'   => 'field_type',
            'field_params' => ['options' => array_merge([''=>'Select Field Type'], Soda::getFormBuilder()->getFieldTypes())]
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Field Default Value',
            'field_type'  => 'text',
            'field_name'  => 'value',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Field Name',
            'field_type'  => 'text',
            'field_name'  => 'field_name',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Field Description',
            'field_type'  => 'textarea',
            'field_name'  => 'description',
        ])->setModel($model) !!}

		{{-- TODO: https://github.com/josdejong/jsoneditor --}}
		{!! Soda::field([
            'name'        => 'Field Parameters',
            'field_type'  => 'json',
            'field_name'  => 'field_params',
        ])->setModel($model) !!}
        
		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
