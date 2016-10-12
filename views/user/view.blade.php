@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.user') }}">Users</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New User' }}</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | User</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-user-plus',
    'title'       => $model->username ? ' User: ' . $model->username : 'New User',
])

@section('content')
    <form method="POST" action='{{route('soda.field.edit',['id'=>@$model->id])}}' class="form--wrapper"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
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
            <button class="btn btn-primary">
                <i class="fa fa-pencil"></i>
                <span>Update</span>
            </button>
        @else
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span>Create</span>
            </button>
        @endif
    </form>
@endsection
