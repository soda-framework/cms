@extends(soda_cms_view_path('layouts.inner'))

@section('header')

	<title>User</title>

@endsection

@section('content')
	@include(soda_cms_view_path('partials.heading'),['icon'=>$model->id?'fa fa-user':'fa fa-user-plus', 'title'=>$model->name?'Field: '.$model->name:'New User'])
	<form method="POST" action='{{route('soda.field.edit',['id'=>@$model->id])}}' class="form--wrapper" enctype="multipart/form-data">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
		{!! SodaForm::text([
            "name"        => "Username",
            "field_name"  => 'username',
        ])->setModel($model) !!}

		{!! SodaForm::text([
            "name"        => "Email",
            "field_name"  => 'email',
        ])->setModel($model) !!}

		{!! SodaForm::dropdown([
            "name"         => "Role",
            "field_name"   => 'role_id',
            "field_params" => ["options" => array_merge(['' => 'Select Role'], Soda\Cms\Models\Role::lists('name','id')->toArray())]
        ])->setModel($model) !!}

		@if($model->id)
			<button class="btn btn-primary"><span class="fa fa-pencil"></span> Update</button>
		@else
			<button class="btn btn-primary"><span class="fa fa-plus"></span> Create</button>
		@endif
	</form>
@endsection
