<?php

use Illuminate\Database\Seeder;

class BlockTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('block_types')->delete();
        
        \DB::table('block_types')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'Events',
                'description' => 'Items used on the events page',
                'application_users_id' => 5,
                'application_id' => 1,
                'action' => 'partials.sections.banner',
                'action_type' => 'view',
                'package' => 'optus',
                'created_at' => '2016-02-23 06:29:32',
                'updated_at' => '2016-02-23 06:28:50',
                'identifier' => 'events',
                'status_id' => 1,
                'edit_action' => 'view',
                'edit_action_type' => 'soda::blocks.index',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Projects',
                'description' => 'Items used on the projects page',
                'application_users_id' => 5,
                'application_id' => 1,
                'action' => 'partials.sections.banner',
                'action_type' => 'view',
                'package' => 'optus',
                'created_at' => '2016-02-23 06:29:32',
                'updated_at' => '2016-02-23 06:28:50',
                'identifier' => 'projects',
                'status_id' => 1,
                'edit_action' => 'view',
                'edit_action_type' => 'soda::blocks.index',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Murals',
                'description' => 'Items used on the murals page',
                'application_users_id' => 5,
                'application_id' => 1,
                'action' => 'partials.sections.banner',
                'action_type' => 'view',
                'package' => 'optus',
                'created_at' => '2016-02-23 06:29:32',
                'updated_at' => '2016-02-23 06:28:50',
                'identifier' => 'murals',
                'status_id' => 1,
                'edit_action' => 'view',
                'edit_action_type' => 'soda::blocks.index',
            ),
        ));
        
        
    }
}
