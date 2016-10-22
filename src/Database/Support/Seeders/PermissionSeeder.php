<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $permissionModel = app('soda.permission.model');
        $roleModel = app('soda.role.model');

        $role_guest = $roleModel->create([
            'name'         => 'guest',
            'display_name' => 'Guest',
            'description'  => 'Generic guest role.',
        ]);

        $role_user = $roleModel->create([
            'name'         => 'user',
            'display_name' => 'User',
            'description'  => 'Generic user role',
        ]);
        
        $role_developer = $roleModel->create([
            'name'         => 'developer',
            'display_name' => 'Developer',
            'description'  => 'Developers gain access to additional functionality',
        ]);

        $role_admin = $roleModel->create([
            'name'         => 'admin',
            'display_name' => 'Admin',
            'description'  => 'Admins have high-level access to the CMS. Recommended for clients.',
        ]);

        $permission_access_cms = $permissionModel->create([
            'name'         => 'access-cms',
            'display_name' => 'Access CMS',
            'description'  => 'Allow login/access to CMS.',
            'category'     => 'Application',
        ]);

        $permission_view_drafts = $permissionModel->create([
            'name'         => 'view-drafts',
            'display_name' => 'View Drafts',
            'description'  => 'View content in draft mode on the live site.',
            'category'     => 'Application',
        ]);

        $permission_view_users = $permissionModel->create([
            'name'         => 'view-users',
            'display_name' => 'View Users',
            'description'  => 'View list of users and their details.',
            'category'     => 'Users',
        ]);

        $permission_edit_users = $permissionModel->create([
            'name'         => 'edit-users',
            'display_name' => 'Edit Users',
            'description'  => 'Edit user profile information.',
            'category'     => 'Users',
        ]);

        $permission_create_users = $permissionModel->create([
            'name'         => 'create-users',
            'display_name' => 'Create Users',
            'description'  => 'Create new user accounts.',
            'category'     => 'Users',
        ]);

        $permission_delete_users = $permissionModel->create([
            'name'         => 'delete-users',
            'display_name' => 'Delete Users',
            'description'  => 'Delete existing user accounts.',
            'category'     => 'Users',
        ]);

        $permission_assign_user_roles = $permissionModel->create([
            'name'         => 'assign-user-roles',
            'display_name' => 'Assign User-roles',
            'description'  => 'Assign roles to users.',
            'category'     => 'Users',
        ]);

        $permission_view_roles = $permissionModel->create([
            'name'         => 'view-roles',
            'display_name' => 'View Roles',
            'description'  => 'View list of roles and their details.',
            'category'     => 'Roles',
        ]);

        $permission_edit_roles = $permissionModel->create([
            'name'         => 'edit-roles',
            'display_name' => 'Edit Roles',
            'description'  => 'Edit role information.',
            'category'     => 'Roles',
        ]);

        $permission_create_roles = $permissionModel->create([
            'name'         => 'create-roles',
            'display_name' => 'Create Roles',
            'description'  => 'Create new roles.',
            'category'     => 'Roles',
        ]);

        $permission_delete_roles = $permissionModel->create([
            'name'         => 'delete-roles',
            'display_name' => 'Delete Roles',
            'description'  => 'Delete existing roles.',
            'category'     => 'Roles',
        ]);

        $permission_view_permissions = $permissionModel->create([
            'name'         => 'view-permissions',
            'display_name' => 'View Permissions',
            'description'  => 'View list of permissions and their details.',
            'category'     => 'Permissions',
        ]);

        $permission_edit_permissions = $permissionModel->create([
            'name'         => 'edit-permissions',
            'display_name' => 'Edit Permissions',
            'description'  => 'Edit permission information.',
            'category'     => 'Permissions',
        ]);

        $permission_create_permissions = $permissionModel->create([
            'name'         => 'create-permission',
            'display_name' => 'Create Permissions',
            'description'  => 'Create new permission.',
            'category'     => 'Permissions',
        ]);

        $permission_delete_permissions = $permissionModel->create([
            'name'         => 'delete-permission',
            'display_name' => 'Delete Permissions',
            'description'  => 'Delete existing permissions.',
            'category'     => 'Permissions',
        ]);

        $permission_assign_role_permissions = $permissionModel->create([
            'name'         => 'assign-role-permissions',
            'display_name' => 'Assign Role-permissions',
            'description'  => 'Assign permissions to roles.',
            'category'     => 'Permissions',
        ]);

        $permission_view_pages = $permissionModel->create([
            'name'         => 'view-pages',
            'display_name' => 'View Pages',
            'description'  => 'View list of pages and their contents.',
            'category'     => 'Pages',
        ]);

        $permission_edit_pages = $permissionModel->create([
            'name'         => 'edit-pages',
            'display_name' => 'Edit Pages',
            'description'  => 'Edit page content.',
            'category'     => 'Pages',
        ]);

        $permission_create_pages = $permissionModel->create([
            'name'         => 'create-pages',
            'display_name' => 'Create Pages',
            'description'  => 'Create new pages.',
            'category'     => 'Pages',
        ]);

        $permission_delete_pages = $permissionModel->create([
            'name'         => 'delete-pages',
            'display_name' => 'Delete Pages',
            'description'  => 'Delete existing pages.',
            'category'     => 'Pages',
        ]);

        $permission_manage_page_types = $permissionModel->create([
            'name'         => 'manage-page-types',
            'display_name' => 'Manage Page Types',
            'description'  => 'Manage types to build pages from.',
            'category'     => 'Pages',
        ]);

        $permission_advanced_pages = $permissionModel->create([
            'name'         => 'advanced-pages',
            'display_name' => 'Pages (advanced options)',
            'description'  => 'Access advanced page options.',
            'category'     => 'Pages',
        ]);

        $permission_view_blocks = $permissionModel->create([
            'name'         => 'view-blocks',
            'display_name' => 'View Blocks',
            'description'  => 'View list of blocks and their contents.',
            'category'     => 'Blocks',
        ]);

        $permission_edit_blocks = $permissionModel->create([
            'name'         => 'edit-blocks',
            'display_name' => 'Edit Blocks',
            'description'  => 'Edit blocks content.',
            'category'     => 'Blocks',
        ]);

        $permission_create_blocks = $permissionModel->create([
            'name'         => 'create-blocks',
            'display_name' => 'Create Blocks',
            'description'  => 'Create new blocks.',
            'category'     => 'Blocks',
        ]);

        $permission_delete_blocks = $permissionModel->create([
            'name'         => 'delete-blocks',
            'display_name' => 'Delete Blocks',
            'description'  => 'Delete existing blocks.',
            'category'     => 'Blocks',
        ]);

        $permission_manage_block_types = $permissionModel->create([
            'name'         => 'manage-block-types',
            'display_name' => 'Manage Block Types',
            'description'  => 'Manage types to build blocks from.',
            'category'     => 'Blocks',
        ]);

        $permission_advanced_blocks = $permissionModel->create([
            'name'         => 'advanced-blocks',
            'display_name' => 'Blocks (advanced options)',
            'description'  => 'Access advanced block options.',
            'category'     => 'Blocks',
        ]);

        $permission_manage_fields = $permissionModel->create([
            'name'         => 'manage-fields',
            'display_name' => 'Manage Fields',
            'description'  => 'Manage fields association with page/block content.',
            'category'     => 'Fields',
        ]);

        $role_developer->attachPermissions([
            $permission_manage_page_types,
            $permission_advanced_pages,
            $permission_manage_block_types,
            $permission_advanced_blocks,
            $permission_manage_fields,
            $permission_view_drafts,
            $permission_view_blocks,
            $permission_edit_blocks,
            $permission_create_blocks,
            $permission_delete_blocks,
            $permission_view_roles,
            $permission_edit_roles,
            $permission_create_roles,
            $permission_delete_roles,
            $permission_assign_role_permissions,
            $permission_view_permissions,
            $permission_edit_permissions,
            $permission_create_permissions,
            $permission_delete_permissions,
        ]);

        $role_admin->attachPermissions([
            $permission_access_cms,
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
