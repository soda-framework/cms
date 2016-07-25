<?php

use Illuminate\Database\Seeder;

class ApplicationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('applications')->delete();
        
        \DB::table('applications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'my site',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
