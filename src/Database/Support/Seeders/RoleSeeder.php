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
            'name'         => 'guest',
            'display_name' => 'Guest',
            'description'  => 'Generic guest role.',
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'user',
            'display_name' => 'User',
            'description'  => 'Generic user role',
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'developer',
            'display_name' => 'Developer',
            'description'  => 'Developers gain access to additional functionality',
        ]);

        $roleModel->firstOrCreate([
            'name'         => 'admin',
            'display_name' => 'Admin',
            'description'  => 'Admins have high-level access to the CMS. Recommended for clients.',
        ]);
    }
}
