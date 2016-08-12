<?php

use Illuminate\Database\Seeder;

class ApplicationUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('application_users')->delete();
        
        \DB::table('application_users')->insert(array (
            0 => 
            array (
                'id' => 5,
                'application_id' => 1,
                'user_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
