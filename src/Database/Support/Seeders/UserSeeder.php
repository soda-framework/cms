<?php

namespace Soda\Cms\Database\Support\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Soda\Cms\Database\User\Interfaces\RoleInterface;
use Soda\Cms\Database\User\Interfaces\UserInterface;

class UserSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $developer = app(UserInterface::class)->create([
            'username' => 'Developer',
            'email'    => 'developer@admin.com',
            'password' => Hash::make('madeinkatana'),
        ]);

        $admin = app(UserInterface::class)->create([
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $admin_role = app(RoleInterface::class)->where('name', 'admin')->first();
        $developer_role = app(RoleInterface::class)->where('name', 'developer')->first();

        if ($admin_role) {
            $admin->attachRole($admin_role);
        }

        if ($developer_role) {
            $developer->attachRole($developer_role);
            $developer->attachRole($admin_role);
        }
    }
}
