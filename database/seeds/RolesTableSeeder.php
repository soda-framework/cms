<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'superuser',
                'description' => 'super users can do everything',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
