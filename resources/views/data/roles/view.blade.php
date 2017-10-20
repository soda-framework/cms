@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.save'), ['submits' => '#role-form'])
@stop

@section('content')
    <div class="content-block">
        <form method="POST" id="role-form" action="{{ route('soda.roles.' . ($role->id ? 'update' : 'store'), $role->id) }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field($role->id ? 'PUT' : 'POST') !!}

            {!! app('soda.form')->text([
                "name"        => "Name",
                "field_name"  => 'name',
            ])->setModel($role) !!}

            {!! app('soda.form')->text([
                "name"        => "Display Name",
                "field_name"  => 'display_name',
            ])->setModel($role) !!}

            {!! app('soda.form')->textarea([
                "name"        => "Description",
                "field_name"  => 'description',
            ])->setModel($role) !!}

            {!! app('soda.form')->text([
                "name"         => "Level",
                "field_name"   => 'level',
                "description"  => 'Access level for role. Users cannot modify users or roles of another user or role if its level is higher than their own.',
            ])->setModel($role) !!}

            @permission('assign-role-permissions')
            <?php $rolePermissions = $role->permissions->pluck('id')->toArray(); ?>
            <fieldset class="form-group form-group--stacked field_level ">
                <div class="form-group__label">
                    <label for="field_level">Permissions</label>
                </div>
                <div class="form-group__element">
                    <div class="tabbed-table-container">
                        <ul class="nav nav-pills nav-pills--tabbed">
                            @foreach($permissionIds as $permissionCategory => $permissions)
                                <li role="presentation" {!! $loop->first ? 'class="active"' : '' !!}>
                                    <a href="#{{ strtolower($permissionCategory) }}" data-toggle="pill">{{ $permissionCategory }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($permissionIds as $permissionCategory => $permissions)
                                <div id="{{ strtolower($permissionCategory) }}" class="tab-pane fade {{ $loop->first ? 'in active' : '' }}">
                                    <table class="table table-striped">
                                        @foreach($permissions as $permissionId => $permissionName)
                                            <tr>
                                                <td class="middle">
                                                    {{ $permissionName }}
                                                </td>
                                                <td width="66" class="middle">
                                                    <div class="toggle-switch">
                                                        <input id="permission_id_{{ $permissionId }}" type="checkbox" name="permission_id[]" value="{{ $permissionId }}" {!! in_array($permissionId, $rolePermissions) ? 'checked="checked"' : '' !!} />
                                                        <label for="permission_id_{{ $permissionId }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </fieldset>
            @else
                {!! app('soda.form')->static_text([
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
