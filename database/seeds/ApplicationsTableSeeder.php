<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('applications')->delete();

        DB::table('applications')->insert([
                [
                    'name'       => 'Soda CMS',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
        ]);
    }
}
