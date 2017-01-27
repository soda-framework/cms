<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $permissionModel = app('soda.permission.model');
        $roleModel = app('soda.role.model');

        $developer = $roleModel->withoutGlobalScope('in-application')->where('name', 'developer')->first();
        $super_admin = $roleModel->withoutGlobalScope('in-application')->where('name', 'super-admin')->first();
        $admin = $roleModel->withoutGlobalScope('in-application')->where('name', 'admin')->first();

        $permission_access_cms = $permissionModel->firstOrCreate([
            'name'         => 'access-cms',
            'display_name' => 'Access CMS',
            'description'  => 'Allow login/access to CMS.',
            'category'     => 'Application',
        ]);

        $permission_view_drafts = $permissionModel->firstOrCreate([
            'name'         => 'view-drafts',
            'display_name' => 'View Drafts',
            'description'  => 'View content in draft mode on the live site.',
            'category'     => 'Application',
        ]);

        $permission_view_application_settings = $permissionModel->firstOrCreate([
            'name'         => 'view-application-settings',
            'display_name' => 'View Application Settings',
            'description'  => 'View settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permission_edit_application_settings = $permissionModel->firstOrCreate([
            'name'         => 'edit-application-settings',
            'display_name' => 'Edit Application Settings',
            'description'  => 'Edit settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        /*

        $permission_create_application_settings = $permissionModel->create([
            'name'         => 'create-application-settings',
            'display_name' => 'Create Application Settings',
            'description'  => 'Create settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permission_delete_application_settings = $permissionModel->create([
            'name'         => 'delete-application-settings',
            'display_name' => 'Delete Application Settings',
            'description'  => 'Delete settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        */

        $permission_manage_application_urls = $permissionModel->firstOrCreate([
            'name'         => 'manage-application-urls',
            'display_name' => 'Manage Application Urls',
            'description'  => 'Manage urls assoicated with the application.',
            'category'     => 'Application',
        ]);

        $permission_view_users = $permissionModel->firstOrCreate([
            'name'         => 'view-users',
            'display_name' => 'View Users',
            'description'  => 'View list of users and their details.',
            'category'     => 'Users',
        ]);

        $permission_edit_users = $permissionModel->firstOrCreate([
            'name'         => 'edit-users',
            'display_name' => 'Edit Users',
            'description'  => 'Edit user profile information.',
            'category'     => 'Users',
        ]);

        $permission_create_users = $permissionModel->firstOrCreate([
            'name'         => 'create-users',
            'display_name' => 'Create Users',
            'description'  => 'Create new user accounts.',
            'category'     => 'Users',
        ]);

        $permission_delete_users = $permissionModel->firstOrCreate([
            'name'         => 'delete-users',
            'display_name' => 'Delete Users',
            'description'  => 'Delete existing user accounts.',
            'category'     => 'Users',
        ]);

        $permission_assign_user_roles = $permissionModel->firstOrCreate([
            'name'         => 'assign-user-roles',
            'display_name' => 'Assign User-roles',
            'description'  => 'Assign roles to users.',
            'category'     => 'Users',
        ]);

        $permission_view_roles = $permissionModel->firstOrCreate([
            'name'         => 'view-roles',
            'display_name' => 'View Roles',
            'description'  => 'View list of roles and their details.',
            'category'     => 'Roles',
        ]);

        $permission_edit_roles = $permissionModel->firstOrCreate([
            'name'         => 'edit-roles',
            'display_name' => 'Edit Roles',
            'description'  => 'Edit role information.',
            'category'     => 'Roles',
        ]);

        $permission_create_roles = $permissionModel->firstOrCreate([
            'name'         => 'create-roles',
            'display_name' => 'Create Roles',
            'description'  => 'Create new roles.',
            'category'     => 'Roles',
        ]);

        $permission_delete_roles = $permissionModel->firstOrCreate([
            'name'         => 'delete-roles',
            'display_name' => 'Delete Roles',
            'description'  => 'Delete existing roles.',
            'category'     => 'Roles',
        ]);

        $permission_view_permissions = $permissionModel->firstOrCreate([
            'name'         => 'view-permissions',
            'display_name' => 'View Permissions',
            'description'  => 'View list of permissions and their details.',
            'category'     => 'Permissions',
        ]);

        $permission_edit_permissions = $permissionModel->firstOrCreate([
            'name'         => 'edit-permissions',
            'display_name' => 'Edit Permissions',
            'description'  => 'Edit permission information.',
            'category'     => 'Permissions',
        ]);

        $permission_create_permissions = $permissionModel->firstOrCreate([
            'name'         => 'create-permission',
            'display_name' => 'Create Permissions',
            'description'  => 'Create new permission.',
            'category'     => 'Permissions',
        ]);

        $permission_delete_permissions = $permissionModel->firstOrCreate([
            'name'         => 'delete-permission',
            'display_name' => 'Delete Permissions',
            'description'  => 'Delete existing permissions.',
            'category'     => 'Permissions',
        ]);

        $permission_assign_role_permissions = $permissionModel->firstOrCreate([
            'name'         => 'assign-role-permissions',
            'display_name' => 'Assign Role-permissions',
            'description'  => 'Assign permissions to roles.',
            'category'     => 'Permissions',
        ]);

        $permission_manage_own_role_permissions = $permissionModel->firstOrCreate([
            'name'         => 'manage-own-permissions',
            'display_name' => 'Manage Own Permissions',
            'description'  => 'Manage roles and permissions for own account.',
            'category'     => 'Roles',
        ]);

        $permission_view_pages = $permissionModel->firstOrCreate([
            'name'         => 'view-pages',
            'display_name' => 'View Pages',
            'description'  => 'View list of pages and their contents.',
            'category'     => 'Pages',
        ]);

        $permission_edit_pages = $permissionModel->firstOrCreate([
            'name'         => 'edit-pages',
            'display_name' => 'Edit Pages',
            'description'  => 'Edit page content.',
            'category'     => 'Pages',
        ]);

        $permission_create_pages = $permissionModel->firstOrCreate([
            'name'         => 'create-pages',
            'display_name' => 'Create Pages',
            'description'  => 'Create new pages.',
            'category'     => 'Pages',
        ]);

        $permission_delete_pages = $permissionModel->firstOrCreate([
            'name'         => 'delete-pages',
            'display_name' => 'Delete Pages',
            'description'  => 'Delete existing pages.',
            'category'     => 'Pages',
        ]);

        $permission_manage_page_types = $permissionModel->firstOrCreate([
            'name'         => 'manage-page-types',
            'display_name' => 'Manage Page Types',
            'description'  => 'Manage types to build pages from.',
            'category'     => 'Pages',
        ]);

        $permission_advanced_pages = $permissionModel->firstOrCreate([
            'name'         => 'advanced-pages',
            'display_name' => 'Pages (advanced options)',
            'description'  => 'Access advanced page options.',
            'category'     => 'Pages',
        ]);

        $permission_attach_blocks = $permissionModel->firstOrCreate([
            'name'         => 'attach-blocks',
            'display_name' => 'Attach Blocks',
            'description'  => 'Attach new block types to pages.',
            'category'     => 'Blocks',
        ]);

        $permission_detach_blocks = $permissionModel->firstOrCreate([
            'name'         => 'detach-blocks',
            'display_name' => 'Detach Blocks',
            'description'  => 'Detach existing block types from pages.',
            'category'     => 'Blocks',
        ]);

        $permission_manage_block_types = $permissionModel->firstOrCreate([
            'name'         => 'manage-block-types',
            'display_name' => 'Manage Block Types',
            'description'  => 'Manage types to build blocks from.',
            'category'     => 'Blocks',
        ]);

        $permission_manage_fields = $permissionModel->firstOrCreate([
            'name'         => 'manage-fields',
            'display_name' => 'Manage Fields',
            'description'  => 'Manage fields association with page/block content.',
            'category'     => 'Fields',
        ]);

        if ($developer) {
            $developer->attachPermissions([
                //$permission_create_application_settings,
                //$permission_delete_application_settings,
                $permission_manage_application_urls,
                $permission_manage_page_types,
                $permission_advanced_pages,
                $permission_manage_block_types,
                $permission_manage_fields,
                $permission_view_drafts,
                $permission_attach_blocks,
                $permission_detach_blocks,
                $permission_view_roles,
                $permission_edit_roles,
                $permission_create_roles,
                $permission_delete_roles,
                $permission_manage_own_role_permissions,
                $permission_assign_role_permissions,
                $permission_view_permissions,
                $permission_edit_permissions,
                $permission_create_permissions,
                $permission_delete_permissions,
            ]);
        }

        if ($super_admin) {
            $admin->attachPermissions([
                $permission_access_cms,
                $permission_view_application_settings,
                $permission_edit_application_settings,
                $permission_view_users,
                $permission_edit_users,
                $permission_create_users,
                $permission_delete_users,
                $permission_assign_user_roles,
                $permission_view_pages,
                $permission_edit_pages,
                $permission_create_pages,
                $permission_delete_pages,
            ]);
        }

        if ($admin) {
            $admin->attachPermissions([
                $permission_access_cms,
                $permission_view_application_settings,
                $permission_edit_application_settings,
                $permission_view_users,
                $permission_edit_users,
                $permission_create_users,
                $permission_delete_users,
                $permission_assign_user_roles,
                $permission_view_pages,
                $permission_edit_pages,
                $permission_create_pages,
                $permission_delete_pages,
            ]);
        }
    }
}
