@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li><a href="{{ route('soda.block_type') }}">Block Types</a></li>
		<li class="active">{{ $model->name ? $model->name : 'New Block Type' }}</li>
	</ol>
@stop

@section('header')
	<title>Edit Block Type</title>
@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'),['icon'=>'fa fa-pencil-square', 'title'=>$model->name?'Block Type: '.$model->name:'New Block Type'])
	<form method="POST" action='{{ route('soda.'.$hint.'.edit',['id'=>@$model->id]) }}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

		{!! SodaForm::text([
            'name'        => 'Block Type Name',
            'field_name'  => 'name',
        ])->setModel($model)->render() !!}

		{!! SodaForm::toggle([
            'name'         => 'Status',
			'description'  => 'Determines whether blocks of this type are visible on the live website',
			'field_name'   => 'status',
			'value'        => Soda\Cms\Components\Status::LIVE,
			'field_params' => ['on-value' => Soda\Cms\Components\Status::LIVE, 'off-value' => Soda\Cms\Components\Status::DRAFT],
        ])->setModel($model) !!}

		{!! SodaForm::textarea([
            'name'        => 'Block Type Description',
            'field_name'  => 'description',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            'name'        => 'Block Type Action',
            'field_name'  => 'action',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            'name'        => 'Block Type Action Type',
            'field_name'  => 'action_type',
            'value'       => 'view',
            'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
        ])->setModel($model) !!}

		{!! SodaForm::text([
            'name'        => 'Block Type Edit Action',
            'field_name'  => 'edit_action',
            'value'       => 'soda::block.page_block_index',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            'name'        => 'Block Type Edit Action Type',
            'field_name'  => 'edit_action_type',
            'value'       => 'view',
            'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes()],
        ])->setModel($model) !!}

		{!! SodaForm::text([
            'name'        => 'Package',
            'field_name'  => 'package',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            'name'        => 'Identifier',
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
