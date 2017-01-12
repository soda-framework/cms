@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.users.index') }}">Users</a></li>
        <li class="active">{{ $user->name ? $user->name : 'New User' }}</li>
    </ol>
@stop

@section('head.title')
    <title>Soda CMS | User</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $user->username ? ' User: ' . $user->username : 'New User',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="user-form" action="{{ route('soda.users.' . ($user->id ? 'update' : 'store'), $user->id) }}">
            {!! csrf_field() !!}
            {!! method_field($user->id ? 'PUT' : 'POST') !!}

            {!! SodaForm::text([
                "name"        => "Username",
                "field_name"  => 'username',
            ])->setModel($user) !!}

            {!! SodaForm::text([
                "name"        => "Email",
                "field_name"  => 'email',
            ])->setModel($user) !!}

            {!! SodaForm::multiselect([
                "name"         => "Role",
                "field_name"   => 'role_id',
                "value"        => $user->roles->pluck('id')->toArray(),
                "field_params" => [
                    "placeholder" => "Select role(s)",
                    "options"     => $roleIds
                ]
            ])->setModel($user) !!}
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
    </div>
@endsection