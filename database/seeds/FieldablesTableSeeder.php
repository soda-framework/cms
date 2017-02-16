<?php

use Illuminate\Database\Seeder;

class FieldablesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fieldables')->delete();

        DB::table('fieldables')->insert([

        ]);
    }
}
