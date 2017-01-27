<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $roleModel = app('soda.role.model');

        $roleModel->firstOrCreate([
            'name'         => 'developer',
            'display_name' => 'Developer',
            'description'  => 'Developers gain access to additional functionality',
            'level'        => 1000,
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'super-admin',
            'display_name' => 'Super Admin',
            'description'  => 'Super Admins have high-level access to the CMS. Recommended for clients.',
            'level'        => 900,
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'admin',
            'display_name' => 'Admin',
            'description'  => 'Admins have high-level access to the CMS, but cannot modify Super Admin accounts.',
            'level'        => 800,
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'user',
            'display_name' => 'User',
            'description'  => 'Generic user role',
            'level'        => 0,
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'guest',
            'display_name' => 'Guest',
            'description'  => 'Generic guest role.',
            'level'        => 0,
        ]);
    }
}
