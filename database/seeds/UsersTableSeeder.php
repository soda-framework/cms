<?php

use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'username'       => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => Hash::make('admin'),
                'role_id'        => 1,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ]);
    }
}
