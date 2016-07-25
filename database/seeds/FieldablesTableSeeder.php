
<?php

use Illuminate\Database\Seeder;

class FieldablesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fieldables')->delete();
        
        \DB::table('fieldables')->insert(array (
            0 => 
            array (
                'id' => 0,
                'field_id' => 1,
                'fieldable_id' => 1,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
            1 => 
            array (
                'id' => 1,
                'field_id' => 2,
                'fieldable_id' => 1,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
            2 => 
            array (
                'id' => 2,
                'field_id' => 3,
                'fieldable_id' => 1,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
            3 => 
            array (
                'id' => 1003,
                'field_id' => 4,
                'fieldable_id' => 2,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
            4 => 
            array (
                'id' => 1004,
                'field_id' => 5,
                'fieldable_id' => 2,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
            5 => 
            array (
                'id' => 1005,
                'field_id' => 6,
                'fieldable_id' => 2,
                'fieldable_type' => 'Soda\\Models\\PageType',
            ),
        ));
        
        
    }
}
