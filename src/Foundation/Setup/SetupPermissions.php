<?php

namespace Soda\Cms\Foundation\Setup;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\Permission;

class SetupPermissions extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $permissionAccessCms = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'access-cms',
            'display_name' => 'Access CMS',
            'description'  => 'Allow login/access to CMS.',
            'category'     => 'Application',
        ]);

        $permissionViewDrafts = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-drafts',
            'display_name' => 'View Drafts',
            'description'  => 'View content in draft mode on the live site.',
            'category'     => 'Application',
        ]);

        $permissionViewApplicationSettings = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-application-settings',
            'display_name' => 'View Application Settings',
            'description'  => 'View settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permissionEditApplicationSettings = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-application-settings',
            'display_name' => 'Edit Application Settings',
            'description'  => 'Edit settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        /*

        $permissionCreateApplicationSettings = Permission::create([
            'name'         => 'create-application-settings',
            'display_name' => 'Create Application Settings',
            'description'  => 'Create settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permissionDeleteApplicationSettings = Permission::create([
            'name'         => 'delete-application-settings',
            'display_name' => 'Delete Application Settings',
            'description'  => 'Delete settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        */

        $permissionManageApplicationUrls = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-application-urls',
            'display_name' => 'Manage Application Urls',
            'description'  => 'Manage urls assoicated with the application.',
            'category'     => 'Application',
        ]);

        $permissionViewUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-users',
            'display_name' => 'View Users',
            'description'  => 'View list of users and their details.',
            'category'     => 'Users',
        ]);

        $permissionEditUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-users',
            'display_name' => 'Edit Users',
            'description'  => 'Edit user profile information.',
            'category'     => 'Users',
        ]);

        $permissionCreateUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-users',
            'display_name' => 'Create Users',
            'description'  => 'Create new user accounts.',
            'category'     => 'Users',
        ]);

        $permissionDeleteUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-users',
            'display_name' => 'Delete Users',
            'description'  => 'Delete existing user accounts.',
            'category'     => 'Users',
        ]);

        $permissionAssignUserRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'assign-user-roles',
            'display_name' => 'Assign User-roles',
            'description'  => 'Assign roles to users.',
            'category'     => 'Users',
        ]);

        $permissionViewRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-roles',
            'display_name' => 'View Roles',
            'description'  => 'View list of roles and their details.',
            'category'     => 'Roles',
        ]);

        $permissionEditRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-roles',
            'display_name' => 'Edit Roles',
            'description'  => 'Edit role information.',
            'category'     => 'Roles',
        ]);

        $permissionCreateRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-roles',
            'display_name' => 'Create Roles',
            'description'  => 'Create new roles.',
            'category'     => 'Roles',
        ]);

        $permissionDeleteRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-roles',
            'display_name' => 'Delete Roles',
            'description'  => 'Delete existing roles.',
            'category'     => 'Roles',
        ]);

        $permissionViewPermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-permissions',
            'display_name' => 'View Permissions',
            'description'  => 'View list of permissions and their details.',
            'category'     => 'Permissions',
        ]);

        $permissionEditPermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-permissions',
            'display_name' => 'Edit Permissions',
            'description'  => 'Edit permission information.',
            'category'     => 'Permissions',
        ]);

        $permissionCreatePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-permission',
            'display_name' => 'Create Permissions',
            'description'  => 'Create new permission.',
            'category'     => 'Permissions',
        ]);

        $permissionDeletePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-permission',
            'display_name' => 'Delete Permissions',
            'description'  => 'Delete existing permissions.',
            'category'     => 'Permissions',
        ]);

        $permissionAssignRolePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'assign-role-permissions',
            'display_name' => 'Assign Role-permissions',
            'description'  => 'Assign permissions to roles.',
            'category'     => 'Permissions',
        ]);

        $permissionManageOwnRolePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-own-permissions',
            'display_name' => 'Manage Own Permissions',
            'description'  => 'Manage roles and permissions for own account.',
            'category'     => 'Roles',
        ]);

        $permissionViewPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-pages',
            'display_name' => 'View Pages',
            'description'  => 'View list of pages and their contents.',
            'category'     => 'Pages',
        ]);

        $permissionEditPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-pages',
            'display_name' => 'Edit Pages',
            'description'  => 'Edit page content.',
            'category'     => 'Pages',
        ]);

        $permissionCreatePages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-pages',
            'display_name' => 'Create Pages',
            'description'  => 'Create new pages.',
            'category'     => 'Pages',
        ]);

        $permissionDeletePages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-pages',
            'display_name' => 'Delete Pages',
            'description'  => 'Delete existing pages.',
            'category'     => 'Pages',
        ]);

        $permissionManageContentTypes = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-content-types',
            'display_name' => 'Manage Content Types',
            'description'  => 'Manage types to build content from.',
            'category'     => 'Pages',
        ]);

        $permissionAdvancedPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'advanced-pages',
            'display_name' => 'Pages (advanced options)',
            'description'  => 'Access advanced page options.',
            'category'     => 'Pages',
        ]);

        $permissionAttachBlocks = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'attach-blocks',
            'display_name' => 'Attach Blocks',
            'description'  => 'Attach new block types to pages.',
            'category'     => 'Blocks',
        ]);

        $permissionDetachBlocks = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'detach-blocks',
            'display_name' => 'Detach Blocks',
            'description'  => 'Detach existing block types from pages.',
            'category'     => 'Blocks',
        ]);

        $permissionManageBlockTypes = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-block-types',
            'display_name' => 'Manage Block Types',
            'description'  => 'Manage types to build blocks from.',
            'category'     => 'Blocks',
        ]);

        $permissionManageFields = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-fields',
            'display_name' => 'Manage Fields',
            'description'  => 'Manage fields association with page/block content.',
            'category'     => 'Fields',
        ]);

        if ($developer = Role::withoutGlobalScopes()->where('name', 'developer')->first()) {
            $developer->attachPermissions([
                //$permissionCreateApplicationSettings,
                //$permissionDeleteApplicationSettings,
                $permissionManageApplicationUrls,
                $permissionManageContentTypes,
                $permissionAdvancedPages,
                $permissionManageBlockTypes,
                $permissionManageFields,
                $permissionViewDrafts,
                $permissionAttachBlocks,
                $permissionDetachBlocks,
                $permissionViewRoles,
                $permissionEditRoles,
                $permissionCreateRoles,
                $permissionDeleteRoles,
                $permissionManageOwnRolePermissions,
                $permissionAssignRolePermissions,
                $permissionViewPermissions,
                $permissionEditPermissions,
                $permissionCreatePermissions,
                $permissionDeletePermissions,
            ]);
        }

        if ($superAdmin = Role::withoutGlobalScopes()->where('name', 'super-admin')->first()) {
            $superAdmin->attachPermissions([
                $permissionAccessCms,
                $permissionViewDrafts,
                $permissionViewApplicationSettings,
                $permissionEditApplicationSettings,
                $permissionViewUsers,
                $permissionEditUsers,
                $permissionCreateUsers,
                $permissionDeleteUsers,
                $permissionAssignUserRoles,
                $permissionViewPages,
                $permissionEditPages,
                $permissionCreatePages,
                $permissionDeletePages,
            ]);
        }

        if ($admin = Role::withoutGlobalScopes()->where('name', 'admin')->first()) {
            $admin->attachPermissions([
                $permissionAccessCms,
                $permissionViewDrafts,
                $permissionViewApplicationSettings,
                $permissionEditApplicationSettings,
                $permissionViewUsers,
                $permissionEditUsers,
                $permissionCreateUsers,
                $permissionDeleteUsers,
                $permissionAssignUserRoles,
                $permissionViewPages,
                $permissionEditPages,
                $permissionCreatePages,
                $permissionDeletePages,
            ]);
        }
    }
}
