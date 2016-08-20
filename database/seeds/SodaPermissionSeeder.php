<?php

use Illuminate\Database\Seeder;
use Soda\Cms\Models\Permission;
use Soda\Cms\Models\Role;

class SodaPermissionSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        $role_developer = Role::create([
            'name'         => 'developer',
            'display_name' => 'Developer',
            'description'  => 'Developers gain access to additional functionality',
        ]);

        $role_admin = Role::create([
            'name'         => 'admin',
            'display_name' => 'Admin',
            'description'  => 'Admins have high-level access to the CMS. Recommended for clients.',
        ]);

        $permission_access_cms = Permission::create([
            'name'         => 'access-cms',
            'display_name' => 'Access CMS',
            'description'  => 'Allow login/access to CMS.',
        ]);

        $permission_view_users = Permission::create([
            'name'         => 'view-users',
            'display_name' => 'View Users',
            'description'  => 'View list of users and their details.',
        ]);

        $permission_edit_users = Permission::create([
            'name'         => 'edit-users',
            'display_name' => 'Edit Users',
            'description'  => 'Edit user profile information.',
        ]);

        $permission_create_users = Permission::create([
            'name'         => 'create-users',
            'display_name' => 'Create Users',
            'description'  => 'Create new user accounts.',
        ]);

        $permission_delete_users = Permission::create([
            'name'         => 'delete-users',
            'display_name' => 'Delete Users',
            'description'  => 'Delete existing user accounts.',
        ]);

        $permission_view_pages = Permission::create([
            'name'         => 'view-pages',
            'display_name' => 'View Pages',
            'description'  => 'View list of pages and their contents.',
        ]);

        $permission_edit_pages = Permission::create([
            'name'         => 'edit-pages',
            'display_name' => 'Edit Pages',
            'description'  => 'Edit page content.',
        ]);

        $permission_create_pages = Permission::create([
            'name'         => 'create-pages',
            'display_name' => 'Create Pages',
            'description'  => 'Create new pages.',
        ]);

        $permission_delete_pages = Permission::create([
            'name'         => 'delete-pages',
            'display_name' => 'Delete Pages',
            'description'  => 'Delete existing pages.',
        ]);

        $permission_manage_page_types = Permission::create([
            'name'         => 'manage-page-types',
            'display_name' => 'Manage Page Types',
            'description'  => 'Manage types to build pages from.',
        ]);

        $permission_advanced_pages = Permission::create([
            'name'         => 'advanced-pages',
            'display_name' => 'Pages (advanced options)',
            'description'  => 'Access advanced page options.',
        ]);

        $permission_view_blocks = Permission::create([
            'name'         => 'view-blocks',
            'display_name' => 'View Blocks',
            'description'  => 'View list of blocks and their contents.',
        ]);

        $permission_edit_blocks = Permission::create([
            'name'         => 'edit-blocks',
            'display_name' => 'Edit Blocks',
            'description'  => 'Edit blocks content.',
        ]);

        $permission_create_blocks = Permission::create([
            'name'         => 'create-blocks',
            'display_name' => 'Create Blocks',
            'description'  => 'Create new blocks.',
        ]);

        $permission_delete_blocks = Permission::create([
            'name'         => 'delete-blocks',
            'display_name' => 'Delete Blocks',
            'description'  => 'Delete existing blocks.',
        ]);

        $permission_manage_block_types = Permission::create([
            'name'         => 'manage-block-types',
            'display_name' => 'Manage Block Types',
            'description'  => 'Manage types to build blocks from.',
        ]);

        $permission_advanced_blocks = Permission::create([
            'name'         => 'advanced-blocks',
            'display_name' => 'Blocks (advanced options)',
            'description'  => 'Access advanced block options.',
        ]);

        $permission_manage_fields = Permission::create([
            'name'         => 'manage-fields',
            'display_name' => 'Manage Fields',
            'description'  => 'Manage fields association with page/block content.',
        ]);

        $role_developer->attachPermissions([
            $permission_manage_page_types,
            $permission_advanced_pages,
            $permission_manage_block_types,
            $permission_advanced_blocks,
            $permission_manage_fields,
        ]);

        $role_admin->attachPermissions([
            $permission_access_cms,
            $permission_view_users,
            $permission_edit_users,
            $permission_create_users,
            $permission_delete_users,
            $permission_view_pages,
            $permission_edit_pages,
            $permission_create_pages,
            $permission_delete_pages,
            $permission_view_blocks,
            $permission_edit_blocks,
            $permission_create_blocks,
            $permission_delete_blocks,
        ]);
    }
}
