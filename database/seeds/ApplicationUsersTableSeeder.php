<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApplicationUsersTableSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        DB::table('application_users')->delete();
        DB::table('application_users')->insert([
            [
                'application_id' => 1,
                'user_id'        => 1,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ]);
    }
}
