@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.roles.index') }}">Roles</a></li>
        <li class="active">{{ $role->id ? $role->display_name : 'New Role' }}</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Role</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#role-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $role->id ? ' Role: ' . $role->display_name : 'New Role',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="role-form" action="{{ route('soda.roles.' . ($role->id ? 'update' : 'store'), $role->id) }}">
            {!! csrf_field() !!}
            {!! method_field($role->id ? 'PUT' : 'POST') !!}

            {!! SodaForm::text([
                "name"        => "Name",
                "field_name"  => 'name',
            ])->setModel($role) !!}

            {!! SodaForm::text([
                "name"        => "Display Name",
                "field_name"  => 'display_name',
            ])->setModel($role) !!}

            {!! SodaForm::textarea([
                "name"        => "Description",
                "field_name"  => 'description',
            ])->setModel($role) !!}

            @permission('assign-role-permissions')
                {!! SodaForm::multiselect([
                    "name"         => "Permissions",
                    "field_name"   => 'permission_id',
                    "value"        => $role->permissions->pluck('id')->toArray(),
                    "field_params" => [
                        "placeholder" => "Select permission(s)",
                        'array-save'  => 'array',
                        "options"     => $permissionIds
                    ]
                ])->setModel($role) !!}
            @else

                {!! SodaForm::static_text([
                    "name"         => "Permissions",
                    "field_name"   => 'permission_id',
                    "value"        => '<ul class="list-inline"><li style="width:33%">' . implode('</li><li style="width:33%">', $role->permissions->pluck('display_name')->toArray()) . '</li></ul>',
                ])->setModel($role) !!}
            @endpermission

        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#role-form'])
    </div>
@endsection
