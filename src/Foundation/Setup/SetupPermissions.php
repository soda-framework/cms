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
            'displayName' => 'Access CMS',
            'description'  => 'Allow login/access to CMS.',
            'category'     => 'Application',
        ]);

        $permissionViewDrafts = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-drafts',
            'displayName' => 'View Drafts',
            'description'  => 'View content in draft mode on the live site.',
            'category'     => 'Application',
        ]);

        $permissionViewApplicationSettings = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-application-settings',
            'displayName' => 'View Application Settings',
            'description'  => 'View settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permissionEditApplicationSettings = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-application-settings',
            'displayName' => 'Edit Application Settings',
            'description'  => 'Edit settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        /*

        $permissionCreateApplicationSettings = Permission::create([
            'name'         => 'create-application-settings',
            'displayName' => 'Create Application Settings',
            'description'  => 'Create settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        $permissionDeleteApplicationSettings = Permission::create([
            'name'         => 'delete-application-settings',
            'displayName' => 'Delete Application Settings',
            'description'  => 'Delete settings used for configuring the application.',
            'category'     => 'Application',
        ]);

        */

        $permissionManageApplicationUrls = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-application-urls',
            'displayName' => 'Manage Application Urls',
            'description'  => 'Manage urls assoicated with the application.',
            'category'     => 'Application',
        ]);

        $permissionViewUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-users',
            'displayName' => 'View Users',
            'description'  => 'View list of users and their details.',
            'category'     => 'Users',
        ]);

        $permissionEditUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-users',
            'displayName' => 'Edit Users',
            'description'  => 'Edit user profile information.',
            'category'     => 'Users',
        ]);

        $permissionCreateUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-users',
            'displayName' => 'Create Users',
            'description'  => 'Create new user accounts.',
            'category'     => 'Users',
        ]);

        $permissionDeleteUsers = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-users',
            'displayName' => 'Delete Users',
            'description'  => 'Delete existing user accounts.',
            'category'     => 'Users',
        ]);

        $permissionAssignUserRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'assign-user-roles',
            'displayName' => 'Assign User-roles',
            'description'  => 'Assign roles to users.',
            'category'     => 'Users',
        ]);

        $permissionViewRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-roles',
            'displayName' => 'View Roles',
            'description'  => 'View list of roles and their details.',
            'category'     => 'Roles',
        ]);

        $permissionEditRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-roles',
            'displayName' => 'Edit Roles',
            'description'  => 'Edit role information.',
            'category'     => 'Roles',
        ]);

        $permissionCreateRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-roles',
            'displayName' => 'Create Roles',
            'description'  => 'Create new roles.',
            'category'     => 'Roles',
        ]);

        $permissionDeleteRoles = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-roles',
            'displayName' => 'Delete Roles',
            'description'  => 'Delete existing roles.',
            'category'     => 'Roles',
        ]);

        $permissionViewPermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-permissions',
            'displayName' => 'View Permissions',
            'description'  => 'View list of permissions and their details.',
            'category'     => 'Permissions',
        ]);

        $permissionEditPermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-permissions',
            'displayName' => 'Edit Permissions',
            'description'  => 'Edit permission information.',
            'category'     => 'Permissions',
        ]);

        $permissionCreatePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-permission',
            'displayName' => 'Create Permissions',
            'description'  => 'Create new permission.',
            'category'     => 'Permissions',
        ]);

        $permissionDeletePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-permission',
            'displayName' => 'Delete Permissions',
            'description'  => 'Delete existing permissions.',
            'category'     => 'Permissions',
        ]);

        $permissionAssignRolePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'assign-role-permissions',
            'displayName' => 'Assign Role-permissions',
            'description'  => 'Assign permissions to roles.',
            'category'     => 'Permissions',
        ]);

        $permissionManageOwnRolePermissions = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-own-permissions',
            'displayName' => 'Manage Own Permissions',
            'description'  => 'Manage roles and permissions for own account.',
            'category'     => 'Roles',
        ]);

        $permissionViewPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'view-pages',
            'displayName' => 'View Pages',
            'description'  => 'View list of pages and their contents.',
            'category'     => 'Pages',
        ]);

        $permissionEditPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'edit-pages',
            'displayName' => 'Edit Pages',
            'description'  => 'Edit page content.',
            'category'     => 'Pages',
        ]);

        $permissionCreatePages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'create-pages',
            'displayName' => 'Create Pages',
            'description'  => 'Create new pages.',
            'category'     => 'Pages',
        ]);

        $permissionDeletePages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'delete-pages',
            'displayName' => 'Delete Pages',
            'description'  => 'Delete existing pages.',
            'category'     => 'Pages',
        ]);

        $permissionManageContentTypes = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-content-types',
            'displayName' => 'Manage Content Types',
            'description'  => 'Manage types to build content from.',
            'category'     => 'Pages',
        ]);

        $permissionAdvancedPages = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'advanced-pages',
            'displayName' => 'Pages (advanced options)',
            'description'  => 'Access advanced page options.',
            'category'     => 'Pages',
        ]);

        $permissionAttachBlocks = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'attach-blocks',
            'displayName' => 'Attach Blocks',
            'description'  => 'Attach new block types to pages.',
            'category'     => 'Blocks',
        ]);

        $permissionDetachBlocks = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'detach-blocks',
            'displayName' => 'Detach Blocks',
            'description'  => 'Detach existing block types from pages.',
            'category'     => 'Blocks',
        ]);

        $permissionManageBlockTypes = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-block-types',
            'displayName' => 'Manage Block Types',
            'description'  => 'Manage types to build blocks from.',
            'category'     => 'Blocks',
        ]);

        $permissionManageFields = Permission::withoutGlobalScopes()->firstOrCreate([
            'name'         => 'manage-fields',
            'displayName' => 'Manage Fields',
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
