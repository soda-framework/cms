<?php

use Illuminate\Database\Seeder;

class PagesClosureTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pages_closure')->delete();
        
        \DB::table('pages_closure')->insert(array (
            0 => 
            array (
                'closure_id' => 1,
                'ancestor' => 1,
                'descendant' => 1,
                'depth' => 0,
            ),
            1 => 
            array (
                'closure_id' => 784,
                'ancestor' => 2,
                'descendant' => 2,
                'depth' => 0,
            ),
            2 => 
            array (
                'closure_id' => 786,
                'ancestor' => 1,
                'descendant' => 3,
                'depth' => 1,
            ),
            3 => 
            array (
                'closure_id' => 787,
                'ancestor' => 3,
                'descendant' => 3,
                'depth' => 0,
            ),
            4 => 
            array (
                'closure_id' => 791,
                'ancestor' => 1,
                'descendant' => 2,
                'depth' => 1,
            ),
        ));
        
        
    }
}
