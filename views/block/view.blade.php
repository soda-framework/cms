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
	@include(config('soda.hint_path').'::partials.heading',['icon'=>'fa fa-square', 'title'=>$model->name?'Block: '.$model->name:'New Block'])
	<form method="POST" action='{{route('soda.block.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />

		{!! Soda::field([
            'name'        => 'Block Name',
            'field_type'  => 'text',
            'field_name'  => 'name',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'         => 'Status',
            'description'  => 'The status of this block',
            'field_type'   => 'dropdown',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Components\Status::LIVE,
            'field_params' => ['options' => Soda\Cms\Components\Status::all()],
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Block Description',
            'field_type'  => 'textarea',
            'field_name'  => 'description',
        ])->setModel($model) !!}

		{!! Soda::field([
            'name'        => 'Block Identifier',
            'field_type'  => 'text',
            'field_name'  => 'identifier',
        ])->setModel($model) !!}

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
