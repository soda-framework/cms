<?php

namespace Soda\Cms\Database\Models\Seeders;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'name'         => 'developer',
            'display_name' => 'Developer',
            'description'  => 'Developers gain access to additional functionality',
            'level'        => 1000,
        ]);

        Role::firstOrCreate([
            'name'         => 'super-admin',
            'display_name' => 'Super Admin',
            'description'  => 'Super Admins have high-level access to the CMS. Recommended for clients.',
            'level'        => 900,
        ]);

        Role::firstOrCreate([
            'name'         => 'admin',
            'display_name' => 'Admin',
            'description'  => 'Admins have high-level access to the CMS, but cannot modify Super Admin accounts.',
            'level'        => 800,
        ]);

        Role::firstOrCreate([
            'name'         => 'user',
            'display_name' => 'User',
            'description'  => 'Generic user role',
            'level'        => 0,
        ]);

        Role::firstOrCreate([
            'name'         => 'guest',
            'display_name' => 'Guest',
            'description'  => 'Generic guest role.',
            'level'        => 0,
        ]);
    }
}
