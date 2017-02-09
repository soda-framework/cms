@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.users.index') }}">Users</a></li>
        <li class="active">{{ $user->name ? $user->name : 'New User' }}</li>
    </ol>
@stop

@section('head.title')
    <title>{{ $user->id ? 'Edit' : 'New' }} User :: Soda CMS</title>
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
        <form method="POST" id="user-form" action="{{ route('soda.users.' . ($user->id ? 'update' : 'store'), $user->id) }}" enctype="multipart/form-data">
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

            @if($user->getLevel() < \Auth::user()->getLevel())
                {!! SodaForm::multiselect([
                    "name"         => "Role",
                    "field_name"   => 'role_id',
                    "value"        => $user->roles->pluck('id')->toArray(),
                    "field_params" => [
                        "placeholder" => "Select role(s)",
                        'array-save'  => 'array',
                        "options"     => $roleIds
                    ]
                ])->setModel($user) !!}

                <hr />
                <br />

                {!! SodaForm::password([
                    "name"        => "Password",
                    "field_name"  => 'password',
                ]) !!}

                {!! SodaForm::password([
                    "name"        => "Password Confirmation",
                    "field_name"  => 'password_confirmation',
                ]) !!}
            @elseif(!$user->id)
                {!! SodaForm::multiselect([
                    "name"         => "Role",
                    "field_name"   => 'role_id',
                    "value"        => $user->roles->pluck('id')->toArray(),
                    "field_params" => [
                        "placeholder" => "Select role(s)",
                        'array-save'  => 'array',
                        "options"     => $roleIds
                    ]
                ])->setModel($user) !!}
            @else
                {!! SodaForm::static_text([
                    "name"         => "Role",
                    "field_name"   => 'role_id',
                    "value"        => $user->roles->implode('display_name', ', '),
                ])->setModel($user) !!}
            @endif
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
    </div>
@endsection
