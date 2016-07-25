<?php

use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fields')->delete();
        
        \DB::table('fields')->insert(array (
            0 => 
            array (
                'id' => 1,
                'field_type' => 'text',
                'field_params' => NULL,
                'value' => 'title',
                'name' => 'title',
                'field_name' => 'title',
                'application_user_id' => 1,
                'description' => 'The title of the home page',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'field_type' => 'fancy_upload',
                'field_params' => NULL,
                'value' => 'image',
                'name' => 'image',
                'field_name' => 'image',
                'application_user_id' => 1,
                'description' => 'An image for the home page',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'field_type' => 'tinymce',
                'field_params' => NULL,
                'value' => 'text',
                'name' => 'text',
                'field_name' => 'text',
                'application_user_id' => 1,
                'description' => 'Text for the homepage',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'field_type' => 'text',
                'field_params' => NULL,
                'value' => 'title',
                'name' => 'title',
                'field_name' => 'title',
                'application_user_id' => 1,
                'description' => 'The title of the about page',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'field_type' => 'checkbox',
                'field_params' => NULL,
                'value' => 'display_time',
                'name' => 'display_time',
                'field_name' => 'display_time',
                'application_user_id' => 1,
                'description' => 'display_time',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'field_type' => 'tinymce',
                'field_params' => NULL,
                'value' => 'text',
                'name' => 'text',
                'field_name' => 'text',
                'application_user_id' => 1,
                'description' => 'About page text',
                'application_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}
