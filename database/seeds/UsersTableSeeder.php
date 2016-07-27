<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$pgH.o1sW49LWf.pOKa7eT.llu4S3Hompr02jRwVzT5vt.osworh6K',
                'remember_token' => 'LQj89omwXIKdl2bTc1bveKVh4fLSENNLdx9j9bDntDSKKWpJO0GzM7GwEooM',
                'created_at' => '2016-07-25 13:51:27',
                'updated_at' => '2016-07-25 13:51:27',
                'role_id' => 1,
            )
        ));
        
        
    }
}
