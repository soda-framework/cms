<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pages')->delete();
        
        \DB::table('pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'root.',
                'slug' => NULL,
                'package' => NULL,
                'action' => NULL,
                'action_type' => NULL,
                'parent_id' => NULL,
                'position' => '0',
                'real_depth' => 0,
                'deleted_at' => NULL,
                'application_user_id' => NULL,
                'application_id' => 1,
                'template_id' => NULL,
                'page_type_id' => NULL,
                'status_id' => 1,
                'edit_action' => NULL,
                'edit_action_type' => NULL,
                'description' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'about',
                'slug' => '/about',
                'package' => 'soda',
                'action' => 'default.about',
                'action_type' => 'view',
                'parent_id' => 1,
                'position' => '1',
                'real_depth' => 1,
                'deleted_at' => NULL,
                'application_user_id' => NULL,
                'application_id' => 1,
                'template_id' => NULL,
                'page_type_id' => 2,
                'status_id' => 1,
                'edit_action' => NULL,
                'edit_action_type' => NULL,
                'description' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'homepage',
                'slug' => '/',
                'package' => 'soda',
                'action' => 'default.homepage',
                'action_type' => 'view',
                'parent_id' => 1,
                'position' => '0',
                'real_depth' => 1,
                'deleted_at' => NULL,
                'application_user_id' => NULL,
                'application_id' => 1,
                'template_id' => NULL,
                'page_type_id' => 1,
                'status_id' => 1,
                'edit_action' => NULL,
                'edit_action_type' => NULL,
                'description' => NULL,
            ),
        ));
        
        
    }
}
