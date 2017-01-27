@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.permissions.index') }}">Permissions</a></li>
        <li class="active">{{ $permission->id ? $permission->display_name : 'New Permission' }}</li>
    </ol>
@stop

@section('head.cms')
    <title>Soda CMS | Permission</title>
@endsection

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#permission-form'])
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-pencil',
    'title'       => $permission->id ? ' Permission: ' . $permission->display_name : 'New Permission',
])

@section('content')
    <div class="content-block">
        <form method="POST" id="permission-form" action="{{ route('soda.permissions.' . ($permission->id ? 'update' : 'store'), $permission->id) }}">
            {!! csrf_field() !!}
            {!! method_field($permission->id ? 'PUT' : 'POST') !!}

            {!! SodaForm::text([
                "name"        => "Name",
                "field_name"  => 'name',
            ])->setModel($permission) !!}

            {!! SodaForm::text([
                "name"        => "Display Name",
                "field_name"  => 'display_name',
            ])->setModel($permission) !!}

            {!! SodaForm::textarea([
                "name"        => "Description",
                "field_name"  => 'description',
            ])->setModel($permission) !!}

            @permission('assign-role-permissions')
                {!! SodaForm::multiselect([
                    "name"         => "Roles",
                    "field_name"   => 'role_id',
                    "value"        => $permission->roles->where('level', '<', Auth::user()->getLevel())->pluck('id')->toArray(),
                    "field_params" => [
                        "placeholder" => "Select roles(s)",
                        'array-save'  => 'array',
                        "options"     => $roleIds
                    ]
                ])->setModel($permission) !!}

                @foreach($permission->roles->where('level', '>=', Auth::user()->getLevel())->pluck('id') as $hiddenRoleInput)
                    <input type="hidden" name="role_id[]" value="{{ $hiddenRoleInput }}" />
                @endforeach
            @else
                {!! SodaForm::static_text([
                    "name"         => "Roles",
                    "field_name"   => 'role_id',
                    "value"        => implode(', ', $permission->roles->pluck('display_name')->toArray()),
                ])->setModel($permission) !!}
            @endpermission
        </form>
    </div>

    <div class="content-bottom">
        @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#permission-form'])
    </div>
@endsection
