@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.user') }}">Users</a></li>
        <li class="active">{{ $model->name ? $model->name : 'New User' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | User</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-user-plus',
    'title'       => $model->username ? ' User: ' . $model->username : 'New User',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="user-form" action='{{route('soda.field.edit',['id'=>@$model->id])}}' enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            {!! SodaForm::text([
                "name"        => "Username",
                "field_name"  => 'username',
            ])->setModel($model) !!}

            {!! SodaForm::text([
                "name"        => "Email",
                "field_name"  => 'email',
            ])->setModel($model) !!}

            {!! SodaForm::multiselect([
                "name"         => "Role",
                "field_name"   => 'role_id',
                "value"        => $model->roles->pluck('id')->toArray(),
                "field_params" => [
                    "placeholder" => "Select role(s)",
                    'array-save'  => 'array',
                    "options"     => array_merge(['' => 'Select Role'], Soda\Cms\Models\Role::pluck('name','id')->toArray())
                ]
            ])->setModel($model) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
    </div>
@endsection
