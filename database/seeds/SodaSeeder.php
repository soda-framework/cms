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
        $this->call('SodaApplicationSeeder');
        $this->call('SodaBlockSeeder');
        $this->call('SodaPermissionSeeder');
        $this->call('SodaUserSeeder');
    }
}
