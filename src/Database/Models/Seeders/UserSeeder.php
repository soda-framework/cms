<?php

namespace Soda\Cms\Database\Models\Seeders;

use Illuminate\Database\Seeder;
use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $developer = User::firstOrCreate([
            'username' => 'Developer',
            'email'    => 'developer@admin.com',
            'password' => Hash::make('madeinkatana'),
        ]);

        $admin = User::firstOrCreate([
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        if ($super_admin_role = Role::where('name', 'super-admin')->first()) {
            $admin->attachRole($super_admin_role);
        }

        if ($developer_role = Role::where('name', 'developer')->first()) {
            $developer->attachRole($developer_role);
            $developer->attachRole($super_admin_role);
        }
    }
}
