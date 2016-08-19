<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        DB::table('pages')->delete();

        DB::table('pages')->insert([

        ]);
    }
}
