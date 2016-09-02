@extends(soda_cms_view_path('layouts.inner'))



@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('soda.home') }}">Home</a></li>
		<li><a href="{{ route('soda.block') }}">Blocks</a></li>
		<li class="active">{{ $model->name ? $model->name : 'New '. ($model->type ? $model->type->name . " Block" : "Block") }}</li>
	</ol>
@stop

@section('header')

	<title>Edit Block</title>

@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'),['icon'=>'fa fa-square', 'title'=> $model->name ? 'Block: ' . $model->name : 'New Block'])
	<form method="POST" action='{{route('soda.block.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<input type="hidden" name="block_type_id" value="{{ $model->block_type_id }}" />

		{!! SodaForm::text([
            'name'        => 'Block Name',
            'field_name'  => 'name',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            'name'         => 'Status',
            'description'  => 'The status of this block',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Components\Status::LIVE,
            'field_params' => ['options' => Soda\Cms\Components\Status::all()],
        ])->setModel($model) !!}

		{!! SodaForm::radio([
            'name'         => 'Shared',
            'field_name'   => 'is_shared',
            'description'  => 'Whether or not the contents of this block should be shared across all pages. Changing this field affects current block contents.',
            'field_params' => ['options' => ['1' => 'Yes', '0' => 'No']],
        ])->setModel($model) !!}

		{!! SodaForm::textarea([
            'name'        => 'Block Description',
            'field_name'  => 'description',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            'name'        => 'Block Identifier',
            'field_name'  => 'identifier',
        ])->setModel($model) !!}

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
