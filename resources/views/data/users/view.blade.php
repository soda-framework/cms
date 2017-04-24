@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
@stop

@section('content')
    <div class="content-block">
        <form method="POST" id="user-form" action="{{ route('soda.users.' . ($user->id ? 'update' : 'store'), $user->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($user->id ? 'PUT' : 'POST') !!}

            {!! app('soda.form')->text([
                "name"        => "Username",
                "field_name"  => 'username',
            ])->setModel($user) !!}

            {!! app('soda.form')->text([
                "name"        => "Email",
                "field_name"  => 'email',
            ])->setModel($user) !!}

            @if($user->getLevel() < \Auth::user()->getLevel())
                {!! app('soda.form')->multiselect([
                    "name"         => "Role",
                    "field_name"   => 'role_id',
                    "value"        => $user->roles->pluck('id')->toArray(),
                    "field_params" => [
                        "placeholder" => "Select role(s)",
                        'array-save'  => 'array',
                        "options"     => $roleIds
                    ]
                ])->setModel($user) !!}
            @elseif(!$user->id)
                {!! app('soda.form')->multiselect([
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
                {!! app('soda.form')->static_text([
                    "name"         => "Role",
                    "field_name"   => 'role_id',
                    "value"        => $user->roles->implode('display_name', ', '),
                ])->setModel($user) !!}
            @endif

            @if($user->getLevel() < \Auth::user()->getLevel() || $user->id == Auth::user()->id)
            <hr />
            <br />

            {!! app('soda.form')->password([
                "name"        => "Password",
                "field_name"  => 'password',
            ]) !!}

            {!! app('soda.form')->password([
                "name"        => "Password Confirmation",
                "field_name"  => 'password_confirmation',
            ]) !!}

            @endif
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#user-form'])
    </div>
@endsection
