<?php

use Illuminate\Database\Seeder;

class SodaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('ApplicationUrlsTableSeeder');
        $this->call('ApplicationPluginsTableSeeder');
        $this->call('ApplicationUsersTableSeeder');
        $this->call('ApplicationsTableSeeder');
        $this->call('BlockTypesTableSeeder');
        $this->call('BlocksTableSeeder');
        $this->call('FieldablesTableSeeder');
        $this->call('FieldsTableSeeder');
        $this->call('PageBlocksTableSeeder');
        $this->call('PageFieldsTableSeeder');
        $this->call('PageTypesTableSeeder');
        $this->call('PagesTableSeeder');
        $this->call('PagesClosureTableSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
    }
}
