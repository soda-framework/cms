<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $developer = app('soda.user.model')->firstOrCreate([
            'username' => 'Developer',
            'email'    => 'developer@admin.com',
            'password' => Hash::make('madeinkatana'),
        ]);

        $admin = app('soda.user.model')->firstOrCreate([
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        if ($admin_role = app('soda.role.model')->where('name', 'admin')->first()) {
            $admin->attachRole($admin_role);
        }

        if ($developer_role = app('soda.role.model')->where('name', 'developer')->first()) {
            $developer->attachRole($developer_role);
            $developer->attachRole($admin_role);
        }
    }
}
