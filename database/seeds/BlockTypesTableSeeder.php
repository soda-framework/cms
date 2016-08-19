<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlockTypesTableSeeder extends Seeder {

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        DB::table('block_types')->delete();
        DB::table('block_types')->insert([
            [
                'id'               => 1,
                'name'             => 'Slider',
                'description'      => 'Slides to appear in a slider plugin',
                'application_id'   => 1,
                'action'           => 'partials.sections.slider',
                'action_type'      => 'view',
                'package'          => 'sodacms',
                'identifier'       => 'slider',
                'status_id'        => 1,
                'edit_action'      => 'view',
                'edit_action_type' => 'soda::blocks.index',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ]);
    }
}
