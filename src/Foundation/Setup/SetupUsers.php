<?php

namespace Soda\Cms\Foundation\Setup;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupUsers extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $developer = User::withoutGlobalScopes()->firstOrCreate([
            'username' => 'Developer',
            'email'    => 'developer@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $admin = User::withoutGlobalScopes()->firstOrCreate([
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        if ($superAdminRole = Role::withoutGlobalScopes()->where('name', 'super-admin')->first()) {
            $admin->attachRole($superAdminRole);
        }

        if ($developerRole = Role::withoutGlobalScopes()->where('name', 'developer')->first()) {
            $developer->attachRole($developerRole);
            $developer->attachRole($superAdminRole);
        }
    }
}
