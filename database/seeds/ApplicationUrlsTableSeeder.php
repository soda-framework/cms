<?php

use Illuminate\Database\Seeder;

class ApplicationUrlsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_urls')->delete();
        
        \DB::table('application_urls')->insert(array (
            0 => 
            array (
                'id' => 1,
                'domain' => 'localhost:8000',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
