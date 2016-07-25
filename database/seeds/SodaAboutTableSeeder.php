<?php

use Illuminate\Database\Seeder;

class SodaAboutTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('soda_about')->delete();
        
        \DB::table('soda_about')->insert(array (
            0 => 
            array (
                'id' => 2,
                'title' => 'some new event title',
                'text' => '<p>Messenger bag fixie health goth cornhole echo park four dollar toast, godard food truck mlkshk locavore helvetica 3 wolf moon selvage tousled irony. Small batch master cleanse meh, mixtape semiotics ennui bitters plaid wolf craft beer. Williamsburg twee franzen, iPhone health goth narwhal semiotics cardigan paleo biodiesel. Cronut cardigan XOXO everyday carry, bicycle rights schlitz plaid mlkshk 3 wolf moon. Twee viral marfa selvage kinfolk salvia trust fund single-origin coffee mumblecore. Etsy skateboard banjo, marfa fanny pack gentrify swag art party. Hashtag whatever polaroid pork belly, pickled meggings pug.</p>',
                'date' => '2014-09-24 16:00:00',
                'display_time' => 1,
                'updated_at' => '2016-07-18 11:31:20',
                'created_at' => NULL,
                'page_id' => 3,
            ),
        ));
        
        
    }
}
