@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
<ol class="breadcrumb">
	<li><a href="{{ route('soda.home') }}">Home</a></li>
	<li><a href="{{ route('soda.page_type') }}">Page Types</a></li>
	<li class="active">{{ $model->name ? $model->name : 'New Page Type' }}</li>
</ol>
@stop

@section('header')
	<title>Page Types</title>
@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'), ['icon'=>'fa fa-edit', 'title'=> $model->name ? 'Page Type: ' . $model->name : 'New Page Type'])
	<form method="POST" action='{{route('soda.'.$hint.'.edit',['id' => $model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

		{!! SodaForm::text([
            "name"        => 'Page Type Name',
            "field_name"  => 'name',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            'name'         => 'Status',
            'description'  => 'The status of this page type',
            'field_name'   => 'status',
            'value'        => Soda\Cms\Components\Status::LIVE,
            'field_params' => ['options' => Soda\Cms\Components\Status::all()],
        ])->setModel($model) !!}

		{!! SodaForm::textarea([
            "name"        => "Page Type Description",
            "field_name"  => 'description',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            "name"        => "Page Type Action",
            "field_name"  => 'action',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            "name"        => "Page Type Action Type",
            "field_name"  => 'action_type',
            'field_params' => ['options' => Soda::getPageBuilder()->getActionTypes(), 'default' => 'view'],
        ])->setModel($model) !!}

		{!! SodaForm::text([
            "name"        => "Package",
            "field_name"  => 'package',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            "name"        => "Identifier",
            "field_name"  => 'identifier',
        ])->setModel($model) !!}

		@if($model->name)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
