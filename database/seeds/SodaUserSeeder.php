<?php

use Illuminate\Database\Seeder;
use Soda\Cms\Models\Role;
use Soda\Cms\Models\User;

class SodaUserSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        $developer = User::create([
            'username' => 'Developer',
            'email'    => 'developer@admin.com',
            'password' => Hash::make('katana12'),
        ]);

        $admin = User::create([
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $admin_role = Role::whereName('admin')->first();
        $developer_role = Role::whereName('developer')->first();

        if($admin_role) {
            $admin->attachRole($admin_role);
        }

        if($developer_role) {
            $developer->attachRole($developer_role);
            $developer->attachRole($admin_role);
        }
    }
}
