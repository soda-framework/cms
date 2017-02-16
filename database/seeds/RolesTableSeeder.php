<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        DB::table('roles')->insert([
            [
                'name'        => 'Super Admin',
                'description' => 'Super Admins can do everything',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);
    }
}
