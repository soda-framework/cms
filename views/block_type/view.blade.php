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
	<form method="POST" action='{{ route('soda.'.$hint.'.edit',['id'=>@$model->id]) }}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

		{!! Soda::field([
            'name'        => 'Block Type Name',
            'field_type'  => 'text',
            'field_name'  => 'name',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Block Type Description',
            'field_type'  => 'textarea',
            'field_name'  => 'description',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Block Type Action',
            'field_type'  => 'text',
            'field_name'  => 'action',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Block Type Action Type',
            'field_type'  => 'text',
            'field_name'  => 'action_type',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Package',
            'field_type'  => 'text',
            'field_name'  => 'package',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Identifier',
            'field_type'  => 'text',
            'field_name'  => 'identifier',
        ])->setModel($model) !!}

		<h2>TODO: add fields for this block type</h2>

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
