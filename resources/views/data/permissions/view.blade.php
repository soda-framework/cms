@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#permission-form'])
@stop

@section('content')
    <div class="content-block">
        <form method="POST" id="permission-form" action="{{ route('soda.permissions.' . ($permission->id ? 'update' : 'store'), $permission->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($permission->id ? 'PUT' : 'POST') !!}

            {!! app('soda.form')->text([
                "name"        => "Name",
                "field_name"  => 'name',
            ])->setModel($permission) !!}

            {!! app('soda.form')->text([
                "name"        => "Display Name",
                "field_name"  => 'display_name',
            ])->setModel($permission) !!}

            {!! app('soda.form')->textarea([
                "name"        => "Description",
                "field_name"  => 'description',
            ])->setModel($permission) !!}

            @permission('assign-role-permissions')
                {!! app('soda.form')->multiselect([
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
                {!! app('soda.form')->static_text([
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
