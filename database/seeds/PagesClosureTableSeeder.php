<?php

use Illuminate\Database\Seeder;

class PagesClosureTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages_closure')->delete();

        DB::table('pages_closure')->insert([]);
    }
}
