<?php

use Illuminate\Database\Seeder;

class PageTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('page_types')->delete();

        DB::table('page_types')->insert([
            0 => [
                    'id'                   => 1,
                    'name'                 => 'home',
                    'description'          => 'home',
                    'application_users_id' => 0,
                    'application_id'       => 1,
                    'action'               => 'default.view',
                    'action_type'          => null,
                    'package'              => 'sodacms',
                    'created_at'           => '2016-02-23 06:30:09',
                    'updated_at'           => '2016-02-23 06:30:09',
                    'identifier'           => 'homepage',
                    'status_id'            => 1,
                    'edit_action'          => 'view',
                    'edit_action_type'     => 'soda::blocks.index',
                ],
            1 => [
                    'id'                   => 2,
                    'name'                 => 'about',
                    'description'          => 'about',
                    'application_users_id' => 0,
                    'application_id'       => 1,
                    'action'               => 'default.view',
                    'action_type'          => null,
                    'package'              => 'sodacms',
                    'created_at'           => '2016-02-23 06:30:09',
                    'updated_at'           => '2016-02-23 06:30:09',
                    'identifier'           => 'about',
                    'status_id'            => 1,
                    'edit_action'          => 'view',
                    'edit_action_type'     => 'soda::blocks.index',
                ],
        ]);
    }
}
