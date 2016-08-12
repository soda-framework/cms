<?php

use Illuminate\Database\Seeder;

class SodaHomepageTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('soda_homepage')->delete();
        
        \DB::table('soda_homepage')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'welcome',
                'text' => '<p>this is the homepage</p>',
                'image' => 'https://static.pexels.com/photos/81413/tree-field-horizon-countryside-81413.jpeg',
                'page_id' => 3,
                'created_at' => NULL,
                'updated_at' => '2016-06-07 06:33:18',
            ),
        ));
        
        
    }
}
