<?php

namespace Soda\Cms\Seeds;

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
        $this->call('Soda\Cms\Seeds\ApplicationSeeder');
        $this->call('Soda\Cms\Seeds\BlockSeeder');
        $this->call('Soda\Cms\Seeds\PermissionSeeder');
        $this->call('Soda\Cms\Seeds\UserSeeder');
    }
}
