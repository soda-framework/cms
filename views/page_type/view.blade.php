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

		{!! Soda::field([
            "name"        => "Page Type Name",
            'field_type'  => 'text',
            "field_name"  => 'name',
        ])->setModel($model) !!}

		{!! Soda::field([
            "name"        => "Page Type Description",
            'field_type'  => 'textarea',
            "field_name"  => 'description',
        ])->setModel($model) !!}

		{!! Soda::field([
            "name"        => "Field Action",
            'field_type'  => 'text',
            "field_name"  => 'action',
        ])->setModel($model) !!}

		{!! Soda::field([
            "name"        => "Field Action Type",
            'field_type'  => 'text',
            "field_name"  => 'action_type',
        ])->setModel($model) !!}

		{!! Soda::field([
            "name"        => "Package",
            'field_type'  => 'text',
            "field_name"  => 'package',
        ])->setModel($model) !!}

		{!! Soda::field([
            "name"        => "Identifier",
            'field_type'  => 'text',
            "field_name"  => 'identifier',
        ])->setModel($model) !!}

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
