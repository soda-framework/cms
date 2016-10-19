<?php

namespace Soda\Cms\Database\Support\Seeders;

use Illuminate\Database\Seeder;

class SeedAll extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ApplicationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
    }
}
